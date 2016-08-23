# Skip Hire Plugin

The Skip Hire plugin is created for Adtrak clients so that we can add the functionality to book and manage skip hires through a WordPress site. It'll allow for payment via PayPal and management of the customers once they are booked.

*Current version: [v1.3.4][master]*

## Installing

1. Grab the latest release of the plugin from GitHub: https://github.com/adtrak/adtrak-skip-hire/releases. You may need access to this until we add a public facing plugin file.
2. Install it one of two ways; Unzip and place in your wp-content/plugins folder or via the wp-admin go to the plugins tab > add new > upload the zip.
3. Enable the plugin in the admin menu.

## Activation

On activation the plugin should create, if they do not already exist, two pages in the WordPress admin. These will be **Booking** and a sub-page **Confirmation**. If they aren't automatically created for some reason, manually create them before continuing.

Add the shortcode `[ash_booking_form]` to the booking page and the shortcode `[ash_booking_confirmation]` to the confirmation page.

## Adding Content

In the sidebar you should see a tab of **Skip Hire** underneath this will be the options to add your Location, Skips, Coupons, Permits and change the settings.

Each of these options follow simple options and should be relatively self explanatory. You don’t have to add all the fields to the skips and any that are not defined will not show on the front end.

For Locations you can be very specific and add a multitude of postcodes (this is more accurate but requires more data entry) or you can be more vague and add generalised locations such as “Nottingham”, “Derby”. If you add postcodes in the settings page change the distance to 0.5 and this will only return results if the user is close to the location, if using generalised locations use a larger distance to cover possible results in that area.

## Settings

In the settings page you'll see a few options. These are relatively simple and will be used by the site mainly when processing details and displaying the form.

### General Settings

**Delivery Radius**
This is the radius you’d like to allow delivery for around the locations you’ve specified

**Email Address**
This is the email that the app will send out any notifications to (when an order is added, payment, etc).

**Enable AM/PM Selector**
This will control whether the user can select afternoon or morning delivery slots

**Enable T&Cs / Terms & Conditions Location**
This will be whether you want to display the terms and the link to read them on the order form.

### Payment Settings

**PayPal Client ID/Secret**
These are used by the app when providing a way for the users to pay via PayPal.

**Payment Description**
Description to show on the invoices

**Payment Number**
The number the user can call if they want to pay you by phone

### Setting up PayPal

You'll need to create a PayPal account / have access to a PayPal account you want to use. If you don't already have an account head https://paypal.com and set up an account. Then follow the following steps below

1. Login to [https://developer.paypal.com/](https://developer.paypal.com/) with your PayPal account.
2. Go to My Apps & Credentials (https://developer.paypal.com/developer/applications/).
3. Scroll down to the REST API apps section. Click the Create App button.
4. Add a name to the app, select the developer account (should default to only one if this is the first time you are doing this).
5. Once created you will be presented with an overview of your app information. Most of this you can ignore, we'll only be needing the Credentials for our app.
6. To test everything is work we'll want to use the Sandbox details. Make sure the Sandbox tab is selected at the top of the page (next to the app name).
7. Under the Sandbox API Credentials copy the Client ID and Secret (you'll need to click show under the secret to get it first). Put both of these into your sites admin panel under **Skip Hire > Settings > Payment**.
8. Add a payment description (so we have a reference for the invoice).
9. Done.

If you've successfully set up those details, you can then test that the PayPal is working with your account buy purchasing a Skip and paying with a PayPal account through the sandbox environment (because it's sandbox it won't take the payments, but will simulate it).

If everything goes through successfully, you can go back to the https://developer.paypal.com/developer/applications, click the app you just created, toggle the live settings at the top of the page and then you'll be presented with a new Client ID / Secret you'll need to add to your site.

*Warning: If using the live credentials any payments processed by the site will be taken, so if you're testing use the **sandbox** credentials*

## Including the Postcode Search

You can include the postcode form anywhere on your site you can declare a shortcode. To do this just include the shortcode `[ash_postcode_form]` anywhere. This will pull in the form, and redirect to the booking page whenever a valid post code is entered.

## Viewing Orders

Orders will be added to the Order tab in the admin. Most of the details will be non-editable and will be for a display view only. There are however a few options which you can change these are the Status of the order, delivery date and delivery slot. Meant only for internal moderation so they can see if skips have been delivered, canceled or paid.
