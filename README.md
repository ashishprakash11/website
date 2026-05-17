# The Signal — WordPress Theme
## Installation & Setup Guide

---

## 1. UPLOAD THE THEME

1. Log in to your WordPress Admin (`yourdomain.com/wp-admin`)
2. Go to **Appearance → Themes → Add New → Upload Theme**
3. Upload `the-signal-theme.zip`
4. Click **Activate**

---

## 2. CREATE THESE CATEGORIES (exact slugs required)

Go to **Posts → Categories** and create:

| Name           | Slug           |
|----------------|----------------|
| Fact Check     | `fact-check`   |
| Geopolitics    | `geopolitics`  |
| Sports         | `sports`       |
| Media          | `media`        |
| Tech           | `tech`         |

> Each category can have a custom accent color — edit the category and pick a color.

---

## 3. CREATE REQUIRED PAGES

Go to **Pages → Add New** and create these pages (exact slugs matter):

| Title                  | Slug                    | Template          |
|------------------------|-------------------------|-------------------|
| About                  | `about`                 | About Page        |
| Contact                | `contact`               | Default           |
| Editorial Standards    | `editorial-standards`   | Default           |
| Fact Check Policy      | `fact-check-policy`     | Default           |
| Corrections            | `corrections`           | Default           |
| Privacy Policy         | `privacy-policy`        | Default           |
| Careers                | `careers`               | Default           |
| Advertise              | `advertise`             | Default           |

For the **About** page: set the template to **About Page** (in the Page Attributes box on the right).

---

## 4. SET UP MENUS

Go to **Appearance → Menus**:

1. Create a menu called **Primary Navigation**
2. Assign it to the **Primary Navigation** location
3. Add any extra links (About, Contact, etc.)

The category nav (Fact Check, Geopolitics, etc.) is **automatic** — it reads your categories.

---

## 5. CUSTOMIZER OPTIONS

Go to **Appearance → Customize → The Signal: General**:

- **Header Tagline** — text shown next to the date (default: "Truth · Context · Clarity")
- **Breaking Ticker** — enter ticker items separated by `|` (leave blank to use latest posts)
- **Newsletter Widget Heading & Description**

---

## 6. BREAKING NEWS TICKER

Go to **The Signal → Ticker Text** in the admin sidebar:
- Enter items separated by `|`
- Example: `PM announces new policy | Markets hit record high | Breaking: earthquake in...`
- Leave blank to automatically use latest post titles

---

## 7. FACT CHECK POSTS

When writing a Fact Check post:
1. Assign the post to the **Fact Check** category
2. In the **Fact Check Details** box (right sidebar of post editor):
   - Set the **Verdict** (False / Misleading / Partially True / Verified)
   - Enter the **Claim** being checked
   - Enter the **Source Note** (where the claim is circulating)

---

## 8. NEWSLETTER SUBSCRIBERS

Go to **The Signal → Subscribers** to view email addresses collected via the newsletter form.

---

## 9. FEATURED POSTS (Hero Section)

To feature a post in the homepage hero:
1. Edit the post
2. Add a custom field: `_is_featured` = `1`
3. (Or install Advanced Custom Fields for a cleaner UI)

---

## 10. RECOMMENDED PLUGINS

| Plugin | Purpose |
|--------|---------|
| **Yoast SEO** or **Rank Math** | SEO meta, Open Graph |
| **WP Super Cache** or **W3 Total Cache** | Performance |
| **Contact Form 7** | Contact page forms |
| **WP-PostViews** | Track post view counts for trending |
| **Classic Editor** (optional) | If you prefer the classic editor |
| **Simple Custom CSS** | For minor style tweaks |

---

## 11. READING TIME

Reading time is auto-calculated at ~200 words/minute. No plugin needed.

---

## 12. DARK / LIGHT MODE

Readers can toggle dark/light mode — their preference is saved in localStorage.
Default is dark mode.

---

## 13. AUTHOR BIOS

For author cards to appear on single posts:
1. Go to **Users → Your Profile**
2. Fill in the **Biographical Info** field
3. Upload a profile photo (uses Gravatar by default)

---

## SUPPORT

For theme support or customization: contact@thesignal.news

---

*The Signal Theme v1.0.0 — Built for independent journalism*
