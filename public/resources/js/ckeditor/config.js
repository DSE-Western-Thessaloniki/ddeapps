/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.indentation = "30px";
    config.entities = false;
    config.entities_greek = false;
    // config.disallowedContent = 'span{font,font-size,font-family}';
    config.autosave = {
        saveDetectionSelectors: "a[id*='Cancel'],button[id*='Save']",
        messageType: "statusbar",
        delay: 30,
        autoLoad: false,
    };
};
