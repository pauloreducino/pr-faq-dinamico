=== PR Dynamic FAQ Accordion ===
Contributors: pauloreducino
Tags: faq, accordion, schema, json-ld, shortcode, dynamic
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple and powerful WordPress plugin that allows you to create dynamic, responsive, and SEO-optimized (with Schema.org) FAQ accordions using shortcodes.

== Description ==

This plugin provides a simple shortcode to create beautiful, responsive, and SEO-friendly FAQ accordions anywhere on your site.

It automatically generates `FAQPage` Schema.org (JSON-LD) based on your content, helping Google understand your Q&A and display them as Rich Results in search.

**Key Features:**

* **Fully Dynamic:** Create accordions with as many questions as you need directly in the page editor.
* **SEO Optimized:** Automatically generates valid `FAQPage` JSON-LD schema.
* **Responsive Design:** Adapts perfectly to desktops, tablets, and mobile devices.
* **Easy to Use:** Just use the `[faq_accordion]` and `[faq_item]` shortcodes.
* **Self-Contained:** Loads all necessary CSS and JS only when the shortcode is used.

== Installation ==

1.  Upload the `pr-faq-dinamico` folder (or the `.zip` file) to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Go to any page or post to add your accordion.

== Frequently Asked Questions ==

= How do I use the plugin? =

This plugin works using two shortcodes that you write directly on your page. You MUST use a **"Custom HTML"** block (in Gutenberg) or the **"Code"** module (in Page Builders like Divi/Elementor) to paste this code.

**Example Code:**

`
[faq_accordion]

  [faq_item question="This is my first question?"]
  <p>Yes, this is the answer to the first question. You can write as much as you want here, including paragraphs.</p>
  [/faq_item]

  [faq_item question="Can I use HTML and lists?"]
  <p>Absolutely! The plugin is built for this. You can use:</p>
  <ul>
    <li>Lists, like this one.</li>
    <li><strong>Bold</strong> and <em>italic</em> text.</li>
    <li>And even <a href="https://yoursite.com">links</a>.</li>
  </ul>
  [/faq_item]

[/faq_accordion]
`

= How do I change the colors? =

You can easily override the default styles by adding CSS to your theme's "Additional CSS" customizer (**Appearance > Customize > Additional CSS**).

**Example:**

`
/* Change the active question color */
.faq-question.active {
    color: #your-color !important;
}

/* Change the answer text color */
.faq-answer {
    color: #your-text-color !important;
}
`

== Changelog ==

= 1.0 =
* Initial release.