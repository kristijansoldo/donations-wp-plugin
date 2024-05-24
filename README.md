# WordPress PayPal Donation Plugin

## Overview
This WordPress plugin enables you to accept donations via PayPal. It supports both PayPal button payments and credit/debit card payments. The plugin is implemented using native Custom Post Types, allowing seamless integration and management within the WordPress environment.

## Features
- **PayPal Button Payments**: Easily add a PayPal donation button to your site.
- **Credit/Debit Card Payments**: Accept donations via credit or debit card.
- **Admin Payment Tracking**: View and manage all donations from the WordPress admin dashboard.
- **Shortcode Generation**: Generate shortcodes to add donation forms to any page or post.
- **Progress Bar**: Display a progress bar showing the target amount and the total amount funded.

## Installation
1. Download the latest release of the plugin from the [GitHub releases page](https://github.com/kristijansoldo/donations-wp-plugin/releases).
2. Sign in to your WordPress dashboard.
3. In the left-hand menu, select `Plugins` > `Add New`.
4. Click the `Upload Plugin` button.
5. Click `Choose File`, select the downloaded plugin zip file, and then click `Install Now`.
6. After the plugin is installed, click `Activate Plugin`.

## Usage
- **Add Donation Form**: Use the provided shortcode `[dp_donation id="<DONATION_ID>"]` to add a PayPal donation form for specific donation to your posts or pages.
- **Add Donation Modal**: Use the shortcode `[dp_donation_modal]` to embed a complete donation form in modal with button with provided all donations.
- **Display Progress Bar**: Use the shortcode `[dp_progress_bar id="<DONATION_ID>"]` to display a progress bar showing the total amount funded towards your goal in percentage.

## Contributing
We welcome contributions to improve this plugin! Follow these steps to set up your development environment:

1. Clone the repository:
   ```sh
   git clone git@github.com:kristijansoldo/donations-wp-plugin.git
   ```
2. Navigate to the plugin directory:
   ```sh
   cd donations-wp-plugin
   ```
3. Install Docker (if not already installed). Follow the instructions on the Docker website for your operating system.
4. Start the Docker containers:
   ```sh
    docker-compose up -d
   ```
5. Open your browser and navigate to http://localhost:8080.
6. Complete the WordPress installation process.
7. Activate the plugin through the 'Plugins' menu in WordPress.
8. Start developing!

## Licence
This plugin is open source and available under the MIT License.

## Contact
For any questions or issues, please open an issue on GitHub or contact me at soldokris@gmail.com


