/*
	Plugin	: Bootstrap Grid CKEditor Plugin
	Author	: Michael Janea (www.michaeljanea.me)
	Version	: 2.0
*/

eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('c l=d;c m=d;c n=d;8.9.o(\'4\',{p:\'4\',t:q(b){m=b.3.u;n=b.3.v;b.w(\'4\',{x:q(a){l=a;y 1=2.z(\'A\');1.B=8.9.e(\'4\')+\'4.C\';1.7.D=\'E\';1.7.F=0;1.7.G=0;1.7.H=0;1.7.I=\'r%\';1.7.J=\'r%\';1.7.K=L;1.M=\'1\';2.f.N(1);2.5(\'1\').6.O=a;2.f.7.P=\'Q\';2.5(\'1\').6.R=a;2.5(\'1\').6.S=a;2.5(\'1\').6.T=a.2.$.f.U;2.5(\'1\').6.g=a.3.g?a.3.g:V;2.5(\'1\').6.h=a.3.h?a.3.h:W;2.5(\'1\').6.i=a.3.i?a.3.i:X;2.5(\'1\').6.j=a.3.j?a.3.j:Y;2.5(\'1\').6.k=a.3.k?a.3.k:12}});b.Z(8.9.e(\'4\')+\'s/4.s\');b.10.o(\'11\',8.13,{14:8.9.e(\'4\')+\'p/4.15\',16:\'4\',17:\'18\'})}});',62,71,'|bootstrapGrid_iframe|document|config|bootstrapGrid|getElementById|contentWindow|style|CKEDITOR|plugins|||var|null|getPath|body|bootstrapGrid_container_extra_large|bootstrapGrid_container_large|bootstrapGrid_container_medium|bootstrapGrid_container_small|bootstrapGrid_grid_columns|bootstrapGrid_parent_window|bootstrapGrid_css_path|bootstrapGrid_js_path|add|icons|function|100|css|init|mj_variables_bootstrap_css_path|mj_variables_bootstrap_js_path|addCommand|exec|let|createElement|iframe|src|html|position|fixed|top|left|border|height|width|zIndex|9999|id|appendChild|CURRENT_CKEDITOR_INSTANCE|overflow|hidden|bootstrapGrid_current_element|bootstrapGrid_current_element_popup|bootstrapGrid_current_content|innerHTML|1140|960|720|540|addContentsCss|ui|BootstrapGrid||UI_BUTTON|icon|png|command|label|Grids'.split('|'),0,{}))

for (var i in CKEDITOR.instances)
{
	CKEDITOR.instances[i].ui.addButton('BootstrapGrid', {
        command : 'bootstrapGrid',
        icon 	: this.path + 'icons/bootstrapGrid.png',
    });
}