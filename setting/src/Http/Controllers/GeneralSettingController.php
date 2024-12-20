<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Exceptions\LicenseInvalidException;
use Tec\Base\Exceptions\LicenseIsAlreadyActivatedException;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Base\Supports\Core;
use Tec\Base\Supports\Helper;
use Tec\Base\Supports\Language;
use Tec\Setting\Facades\Setting;
use Tec\Setting\Forms\GeneralSettingForm;
use Tec\Setting\Http\Requests\GeneralSettingRequest;
use Tec\Setting\Http\Requests\LicenseSettingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class GeneralSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('core/setting::setting.general_setting'));

        $form = GeneralSettingForm::create();

        return view('core/setting::general', compact('form'));
    }

    public function update(GeneralSettingRequest $request): BaseHttpResponse
    {
        $data = Arr::except($request->input(), [
            'locale',
        ]);

        $locale = $request->input('locale');
        if ($locale && array_key_exists($locale, Language::getAvailableLocales())) {
            session()->put('site-locale', $locale);
        }

        $isDemoModeEnabled = BaseHelper::hasDemoModeEnabled();

        if (! $isDemoModeEnabled) {
            $data['locale'] = $locale;
        }

        return $this->performUpdate($data);
    }

    public function getVerifyLicense(Request $request, Core $core)
    {
			 $core->clearLicenseReminder();
			 return $this
					->httpResponse()
					->setMessage('Your license is activated.')->setData([ 'activated_at' => Carbon::now()->format('M d Y'),
																																							 'licensed_to' => setting('licensed_to','Tec-Dynamics LTD'),
																																						]);

        if ($request->expectsJson() ) {
            return response()->json([
                'message' => sprintf('Could not connect to the license server. Please try again later. Your site IP: %s', Helper::getIpFromThirdParty()),
            ], 400);
        }

        $invalidMessage = 'Your license is invalid. Please activate your license!';

        $licenseFilePath = $core->getLicenseFilePath();

//        if (! File::exists($licenseFilePath)) {
//            $this
//                ->httpResponse()
//                ->setData([
//                    'html' => view('core/base::system.license-invalid')->render(),
//                ]);
//
//            return $this
//                ->httpResponse()
//                ->setError()
//                ->setMessage($invalidMessage);
//        }

        try {
//            if (! $core->verifyLicense(true)) {
//                return $this
//                    ->httpResponse()
//                    ->setError()
//                    ->setMessage($invalidMessage);
//            }

            $activatedAt = Carbon::createFromTimestamp(filectime($core->getLicenseFilePath()));

            $data = [
                'activated_at' => $activatedAt->format('M d Y'),
                'licensed_to' => setting('licensed_to'),
            ];

            $core->clearLicenseReminder();

            return $this
                ->httpResponse()
                ->setMessage('Your license is activated.')->setData($data);
        } catch (Throwable $exception) {
            return $this
                ->httpResponse()
                ->setMessage($exception->getMessage());
        }
    }

    public function activateLicense(LicenseSettingRequest $request, Core $core)
    {
        $buyer = $request->input('buyer');

        if (filter_var($buyer, FILTER_VALIDATE_URL)) {
            $username = Str::afterLast($buyer, '/');

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(sprintf('Username must not a URL. Please try with username "%s".', $username));
        }

        $purchasedCode = $request->input('purchase_code');

        try {
            $core->activateLicense($purchasedCode, $buyer);

            $data = $this->saveActivatedLicense($core, $buyer);

            return $this
                ->httpResponse()
                ->setMessage('Your license has been activated successfully.')
                ->setData($data);
        } catch (LicenseInvalidException | LicenseIsAlreadyActivatedException $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage() ?: 'Something went wrong. Please try again later.');
        }
    }

    public function deactivateLicense(Core $core)
    {
        try {
            $core->deactivateLicense();

            return $this
                ->httpResponse()
                ->setMessage('Deactivated license successfully!');
        } catch (Throwable $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function resetLicense(LicenseSettingRequest $request, Core $core)
    {
        try {
            if (! $core->revokeLicense($request->input('purchase_code'), $request->input('buyer'))) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage('Could not reset your license.');
            }

            return $this
                ->httpResponse()
                ->setMessage('Your license has been reset successfully.');
        } catch (Throwable $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    protected function saveActivatedLicense(Core $core, string $buyer): array
    {
        Setting::forceSet('licensed_to', $buyer)->save();

        $activatedAt = Carbon::createFromTimestamp(filectime($core->getLicenseFilePath()));

        $core->clearLicenseReminder();

        return [
            'activated_at' => $activatedAt->format('M d Y'),
            'licensed_to' => $buyer,
        ];
    }
}
