# KalipsoCMS - Changelog

## 04.08.2022 - **v1.0.1.8**
- File auto dimension fix.
- Quill Toolbar - Header buttons styles fixed.
- Quill text aligment styles defined. Quill image resize module is improved for re-open problems.

## 03.08.2022 - **v1.0.1.5**
- Base helper class synchronized with KalipsoNext version.
- Manrope fonts updated to v13.
- ul_attributes and li_levelx_append parameters append to HTML::generateMenu() method.
- Field values left blank in required dynamic component selection fields are set to -1 for ContentController.
- In the dynamic content module, when data can be obtained for the description field, content or title in the module definitions, it is provided to be taken as a variable for the description part of the page.

## 29.07.2022 - **v1.0.1.0**
- externalQuery parameter added to ContentController->getModuleData(). It can be used for specific data query.
- If there is information text on the content and form management screens, it is provided to be displayed.

## 28.07.2022 - **v1.0.0.8**
- The Base helper class has been updated with the KalipsoNext.

## 27.07.2022 - **v1.0.0.7**
- Edited for the corruption that occurs in the case of porting with URL in the helper method that generates and decrypts the password.

## 26.07.2022 - **v1.0.0.6**
- KalipsoTable updated to v0.8.5
- Tabler Icons package updated to v1.78.1

## 25.07.2022 - **v1.0.0.4**
- For the forms that are requested to be submitted for approval again while submitting the form, this control has been ensured since there is a data-kn-again parameter.
- Array return support for HTML::generateMenu helper.

## 22.07.2022 - **v1.0.0.2**
- Fixed an issue where the array has a scalar value when uploading multiple files.

## 22.07.2022 - **v1.0.0.1**
- First release.