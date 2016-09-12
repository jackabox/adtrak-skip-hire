# Changelog

## Version 1.4.1
- [Feature] Added in GA events.
- [Bug] Fixes small issue with order date formatting.
- [Bug] Fixes typo in options settings.

## Version 1.4.0
- [Bug] Fixes an issue with the payment processing on new sites
- [Improvement] Adds a thank you page for PayPal. Option to customise this text is now in the settings.

## Version 1.3.6
- [Bug] Fixes slight issue with sessions and processing orders

## Version 1.3.5
- [Improvement] Adding images for card types

## Version 1.3.4
- [Bug] Sidepatch to fix the issue with CMB2 map not passing through a key.

## Version 1.3.3
- [Bug] Fixing an issue with the data being dumped on the page.

## Version 1.3.2
- [Bug] Fixed an issue with not passing through options data

## Version 1.3.0
- [Improvement] Added the option to set the amount of days to deliver
- [Improvement] Added the option to set the days in the future before being able to pick delivery.

## Version 1.2.3

- [Bug] Caught bug with typo

## Version 1.2.2

- Changing the date field to text so that jquery can hook into it.
- Changed format of date on the output code.

## Version 1.2.1

- [Bug] Fixes an issue with Google Maps API not having a key and access to functions.
- [Bug] Fixes an issue with the Permit defaulting to an unselected type.

## Version 1.2
- [Improvement] Updating the admin to be inline with that of other Adtrak Plugins
- [Improvement] Adds a helper to the settings page which informs the user of the shortcodes/contact email
- [Bug] Fixes minor issue with 4.6 beta 1

## Version 1.1

- [Bug] Fixes issue with incorrect skip passing to final form
- [Bug] Fixes issue with incorrect permit passing to final form
- [Bug] Prefixed admin pages to stop them conflicting with other plugins.
- [Feature] Allowing the user to select whether they want to display the AM/PM time picker.
- [Feature] Allows for showing of terms & conditions on the order-confirmation page.
- [Improvement] Defines certain fields on forms as required using HTML5 attributes.
- [Improvement] Tweaked the admin theme style to play nice with most interfaces.

## Version 1.0.4

- [Improvement] Hides fields on skips which aren't filled out

## Version 1.0.3

- [Improvement] Added classes to overview table
- [Improvement] Added a div wrapper around all of the form content
- [Improvement] Changed the order of the skips

## Version 1.0.2

- [Bug] Fixing typo in code

## Version 1.0.1

- [Bug] Fixing issue with call of CMB2 style
- [Bug] Fixed not being able to add decimals to skips.

## Version 1.0

- [Improvement] Implementing github updater, moving repository to GitHub.
- [Improvement] Integrating Adtrak theme for the admin settings.

## Version 0.4

- [Bug] Fixed break in form when no waste was selected.
- [Bug] Fixed an error where the form was not sending waste details to the database.
- [Feature] Implementing mailer class to send out emails on order confirmation to the admin.
- [Feature] Allowing for user to control admin email in options page.
- [Improvement] Reconstructing user flow and stopping addition of duplicate data.

## Version 0.3
- [Bug] Integrated a regex check for the post code submit to force a post code and stop the form breaking later on.
- [Bug] Fixing coupon being added 100% of time.
- [Bug] Fixing PayPal payment update.
- [Improvement] Adding a settings panel to allow the user to manage settings.
- [Improvement] Cleaning up menu names to make them more readable.
- [Improvement] Improved order overview for quoting when on the phone.
- [Improvement] Tweaks to wording to make it clearer for the user flow.

## Version 0.2
- [Improvement] Restructuring filebase for easier use, declarations in classes as required.
- [Improvement] Removing redundant code to reduce bloat
- [Bug] Fixed issues with PayPal not submitting and processing correctly.

## Version 0.1
- [Feature] Initial Version
