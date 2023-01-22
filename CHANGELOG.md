# KalipsoCMS - Changelog

## 01.22.2023 - **v1.0.4.4**
- Translation files updated.
- Controllers updated.
- Auth middleware redirection parameters added.
- Helper classes updated.
- Cookie consent support added.
- JS fetch body parameter fixed.
- Tabler Icons packages updated to v1.119.0
- Bootstrap packages updated to v5.3.0-alpha-1
- vPjax package changed to jquery-pjax and added jquery-pjax support.
- Added jQuery v3.6.3 package.
- Fixed an error in language definitions that are not in the settings screen.
- Language file checker method added to sandbox mode.

## 01.15.2023 - **v1.0.3.2**
- Translation files updated. Arabic language support added.
- File upload area image size info label added.

## 27.11.2022 - **v1.0.3.0**
- Translation files updated. Macedonian language support added.

## 02.11.2022 - **v1.0.2.9**
- While preparing the auto-complete list in the content controller, an arrangement has been made so that the records with a large number of content are displayed on the top.

## 27.10.2022 - **v1.0.2.8**
- KalipsoNext Core fixes.

## 22.10.2022 - **v1.0.2.7**
- Direct upload dimension-free support.
- FormController variable name bug fixed.
- TablerIcons upgraded to v1.106.0

## 11.10.2022 - **v1.0.2.4**
- `ContentController->extractWidgetData()`: id export support.

## 10.10.2022 - **v1.0.2.3**
- Added hook feature for content controller. In dynamic content modules, you can develop the output directly from the `app/Resources/hook.php` file.

## 09.10.2022 - **v1.0.2.2**
- Fixed the ContentController to provide the corresponding content ID.

## 08.10.2022 - **v1.0.2.1**
- ContentController variable name fix for select type.

## 27.29.2022 - **v1.0.2.0**
- KalipsoNext Core upgraded to v1.0.2.6.

## 09.08.2022 - **v1.0.1.9**
- Fix for unique response in autocomplete feature.

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