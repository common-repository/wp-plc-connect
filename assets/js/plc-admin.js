/**
 * Main Javascript File for WP PLC Connect
 *
 * @package   OnePlace\Connect
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/connect
 */

jQuery(document).ready(function($) {
    /**
     * Show first page
     */
    $('article.plc-admin-page-general').show();

    /**
     * Ajax based navigation
     */
    $('nav.plc-admin-menu ul li a').on('click',function() {
        var sPage = $(this).attr('href').substring('#/'.length);

        $('article.plc-admin-page').hide();
        $('article.plc-admin-page-'+sPage).show();

        return false;
    });

    /**
     * Save Button
     */
    $('button.plc-admin-settings-save').on('click',function() {
        // get page to save
        var sPage = $(this).attr('plc-admin-page');

        // Load all fields for page
        var oData = {};
        $('.plc-admin-'+sPage).find('.plc-settings-value').each(function() {
            var sField = $(this).attr('name');
            var sType = $(this).attr('type');
            if(sType == 'checkbox') {
                oData[sField] = 0;
                if($(this).prop('checked')) {
                    oData[sField] = 1;
                }
            } else {
                oData[sField] = $(this).val();
            }
        });
        // Support for wp_editor field
        if(tinyMCE.activeEditor) {
            oData[tinyMCE.activeEditor.id] = tinyMCE.activeEditor.getContent();
        }
        // show ajax loader
        console.log('get plugin dir');
        console.log(plcAdminControls.pluginUrl);
        $('.plc-admin-alert-container').html('<img style="position:fixed;" src="'+plcAdminControls.pluginUrl+'/assets/img/ajax-loader.gif" />');

        // get nonce
        var sWPNonce = $('#_wpnonce').val();

        // execute ajax
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {action: 'save_plcsettings',settings:oData,nonce:sWPNonce}
        }).done(function( oInfo ) {
            $('.plc-admin-alert-container').html('<div class="plc-alert plc-alert-'+oInfo.state+'" style="position:fixed;">'+oInfo.message+'</div>');
        });

        return false;
    });
});