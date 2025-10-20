# Plugin: PR Dynamic FAQ Accordion

**Version:** 1.0
**Author:** Paulo Reducino

A simple and powerful WordPress plugin that allows you to create dynamic, responsive, and SEO-optimized (with Schema.org) FAQ accordions using shortcodes.

## Key Features

- **Fully Dynamic:** Create as many accordions as you want, with as many questions as you need, directly in the page editor.
- **SEO Optimized:** Automatically generates `FAQPage` Schema.org (JSON-LD) based on your content. This helps Google understand your Q&A and display them as Rich Results in search.
- **Responsive Design:** The accordion is fully responsive and adapts perfectly to desktops, tablets, and mobile devices.
- **Easy to Use:** Just use nested shortcodes (`[faq_accordion]` and `[faq_item]`) directly in your editor.
- **Self-Contained:** All necessary CSS (styles) and JavaScript (click functionality) are loaded optimally only on the pages where the shortcode is used.

---

## Part 1: Plugin Installation

To install this plugin on your WordPress site:

1.  Take your plugin folder (e.g., `pr-faq-dinamico`).
2.  Right-click the folder and compress it into a `.zip` file (e.g., `pr-faq-dinamico.zip`).
3.  In your WordPress dashboard, navigate to **Plugins > Add New**.
4.  Click the **"Upload Plugin"** button at the top of the page.
5.  Click **"Choose File"** and select the `.zip` file you just created.
6.  Click **"Install Now"**.
7.  After the installation is complete, click **"Activate Plugin"**.

Your plugin is now active and ready to use!

---

## Part 2: How to Add the Accordion to Any Page (Step-by-Step)

This plugin works by using two shortcodes that you write directly on your page:

- `[faq_accordion]` - The main container that wraps the entire FAQ block.
- `[faq_item question="Your Question Goes Here"]` - The block for each individual question and answer pair.

### Detailed Step-by-Step Guide:

1.  Access your WordPress dashboard and go to the page or post where you want to add the accordion (e.g., "Pages > All Pages > Your Page").

2.  **This is the most important step:** You must add a block that accepts raw HTML and shortcodes.

    - **If you use the Block Editor (Gutenberg):**

      - Click the `+` icon to add a new block.
      - Search for and select the **"Custom HTML"** block.

    - **If you use a Page Builder (like Divi, Elementor, etc.):**
      - Add a new **Module** (or Widget).
      - Search for and select the **"Code"** module.
      - _(Alternative)_: You can also use a **"Text"** module, but you MUST select the **"Text"** (or "HTML") tab inside it, not the "Visual" tab.

3.  Inside the block you just added (whether "Custom HTML" or "Code"), paste the shortcode structure below.

4.  Customize the content:

    - Change the text inside the quotes `question="..."` to set your question.
    - Write your answer (you can include HTML like `<p>`, `<ul>`, `<strong>`, etc.) _between_ `[faq_item ... ]` and `[/faq_item]`.

5.  Save the page (`Update` or `Publish`).

6.  **Done!** Clear your site's cache (if you use a caching plugin) and visit the page to see your dynamic, SEO-optimized accordion in action.

---

## Code Example (To Copy and Paste)

Copy this code and paste it into your **"Custom HTML"** or **"Code"** block to get started:

```html
[faq_accordion] [faq_item question="This is my first question?"]
<p>
  Yes, this is the answer to the first question. You can write as much as you
  want here, including paragraphs.
</p>
[/faq_item] [faq_item question="Can I use HTML and lists?"]
<p>Absolutely! The plugin is built for this. You can use:</p>
<ul>
  <li>Lists, like this one.</li>
  <li><strong>Bold</strong> and <em>italic</em> text.</li>
  <li>
    And even <a href="[https://yoursite.com](https://yoursite.com)">links</a>.
  </li>
</ul>
[/faq_item] [faq_item question="What is the third question?"]
<p>
  This is the answer to the third question. To add a fourth question, just copy
  and paste another [faq_item] block below this one.
</p>
[/faq_item] [/faq_accordion]
```
