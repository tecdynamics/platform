require('./base/app');
require('./base/layout');
require('./script');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});
// document.addEventListener("DOMContentLoaded", function() {
//     var lazyImages = [].slice.call(document.querySelectorAll("img"));
//     // var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
//
//     if ("IntersectionObserver" in window) {
//         let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
//             entries.forEach(function(entry) {
//                 if (entry.isIntersecting) {
//                     let lazyImage = entry.target;
//                     lazyImage.src = lazyImage.dataset.src;
//                     lazyImage.srcset = lazyImage.dataset.srcset;
//                     lazyImage.attributes.remove("loading");
//                     lazyImageObserver.unobserve(lazyImage);
//                 }
//             });
//         });
//
//
//         lazyImages.forEach(function(lazyImage) {
//             lazyImageObserver.observe(lazyImage);
//         });
//     } else {
//         // Possibly fall back to event handlers here
//     }
// });
