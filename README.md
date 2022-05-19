### Example of integrating with inner blocks in the WooCommerce Blocks Checkout

This repository contains some example code showing how an extension can register an inner block for use in the Checkout
Block offered by [WooCommerce Blocks](https://github.com/woocommerce/woocommerce-blocks).

To integrate a front-end block with WooCommerce Blocks that extends the API, work is required in two areas:
Server-side code that extends the `StoreApi` and front-end code that renders and controls your block.

The overview of how this works is as follows:

#### PHP

- The extension sets up its integration with WooCommerce Blocks by extending the `IntegrationInterface` class:
https://github.com/woocommerce/newsletter-test/blob/main/woocommerce-blocks-integration.php. This class must implement the following methods:
  - `initialize` - called by WooCommerce Blocks to begin the execution of the integration code.
  - `get_name` - the name of your extension, this should be a unique identifier for your extension.
  - `get_script_handles` - Returns an array containing the handles of any scripts registered by our extension.
  - `get_editor_script_handles` - Returns an array containing the handles of any _editor_ scripts registered by our
extension.
  - `get_script_data` - Returns an associative array containing any data we want to be available to the scripts on the
front-end. You can get to it on the front end by accessing `wcSettings['newsletter-test_data']` (The key on `wcSettings`
will be the value returned by `get_name` and `_data`).
- The extension registers this integration when the `woocommerce_blocks_checkout_block_registration` action is fired:
https://github.com/woocommerce/newsletter-test/blob/main/newsletter-test.php#L26-L33
- The `initialize` method calls  `register_frontend_scripts()`, `$this->register_editor_scripts()`, and
`$this->register_editor_blocks()` which registers all the scripts with WordPress. (These will be enqueued later by WooCommerce Blocks, we tell Blocks about the scrips in the `get_script_handles` etc. methods).
- Next, in the `initialize` method, the extension
[extends the API schema](https://github.com/woocommerce/newsletter-test/blob/main/woocommerce-blocks-integration.php#L141).
  - Using the `register_endpoint_data` method on `ExtendSchema` the extension causes the cart endpoint to return
additional data. More information on this can be found at this URL: https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/extensibility/extend-rest-api-add-data.md
- Moving

#### JavaScript

Moving on to JavaScript, we create a block in the same way as you would any regular block, the `parent` property in
`block.json` should reflect the Checkout's inner-block slug where you want your block to appear. Since the newsletter
signup block should appear in the `Contact Information` block the parent we define is
`woocommerce/checkout-contact-information-block`.

Then we create our React component as normal, in this example we have an entry point defined in webpack for the
[`frontend.js`](https://github.com/woocommerce/newsletter-test/blob/main/assets/js/checkout-newsletter-subscription-block/frontend.js) 
file which calls `registerCheckoutBlock` - More information on this can be found on the [Registering a Block Component](https://github.com/woocommerce/woocommerce-blocks/blob/trunk/packages/checkout/blocks-registry/README.md#registercheckoutblock-options-) documentation on WooCommerce Blocks.


As mentioned in the WooCommerce Blocks documentation, the block registered will receive attributes, as defined in
`block.json` and will and have access to any public context providers under the Checkout context.

What this means in practice, is you will have access to the `checkoutExtensionData` object, which contains a function
called `setExtensionData` when this is executed, it sets data in the Checkout context under your extension's namespace.

The signature for this method is: `setExtensionData( namespace: string, key: string, value: any )` but note the value
should be serializable as it gets sent to the API when submitting the checkout form!

