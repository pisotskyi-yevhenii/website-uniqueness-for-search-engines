# GeneratePress Child Theme Uniqueness Demo

This repository contains two independent GeneratePress child themes created for the practical part of the Middle WordPress Full-Stack test assignment.

Both themes use the same educational domain but target different audiences:

- **VibeStart Academy** teaches complete beginners how to build simple projects with AI tools without a traditional coding background.
- **DevAccelerate Lab** teaches working developers how to select, constrain, review, and integrate AI tools into professional development workflows.

## Requirements

- WordPress 7.0 or later
- PHP 8.0 or later
- GeneratePress 3.6.1 or later
- Advanced Custom Fields Free 6.8.4 or later

GeneratePress must remain installed because both products are valid child themes with `Template: generatepress`.

## Installation

1. Copy `vibestart-academy` and `devaccelerate-lab` into `wp-content/themes/`.
2. Install and activate GeneratePress.
3. Install and activate Advanced Custom Fields.
4. Activate either child theme in `Appearance > Themes`.

On first activation, each child theme:

- reuses or creates the single published `Home` page;
- sets `Home` as the static front page;
- creates its own placeholder menus;
- assigns its own menu locations;
- stores its starter content in ACF fields;
- imports its starter header and footer logos into the Media Library.

The initialization is idempotent. Later theme switches do not overwrite content or menu edits made in the WordPress admin.

## Content Editing

All visible site content is editable from the WordPress admin.

### Home Page and Branding

Open `Pages > Home`.

The active theme displays its own ACF field group:

- `VibeStart Home Showcase`
- `DevAccelerate Home Feature Panel`

These fields control the header and footer logos, interface labels, hero content, cards or workflow panels, CTA labels, footer text, copyright text, and theme-specific visual content.

Logo fields use the WordPress Media Library. The bundled PNG images are starter assets and can be replaced from the page editor.

### Menus

Open `Appearance > Menus`.

Each theme owns separate menus for its header and footer. Menu item labels can be edited normally. Every item intentionally remains a custom `#` placeholder and receives `aria-disabled="true"` on the front end.

### Site Title

Open `Settings > General` to edit the browser title and site tagline.

## Product Differences

| Area | VibeStart Academy | DevAccelerate Lab |
| --- | --- | --- |
| Audience | Complete beginners | Working software developers |
| Palette | Indigo, coral, cream, mint | Charcoal, electric lime, cyan, white |
| Heading font | Local Fraunces | Local JetBrains Mono |
| Body font | Local Nunito Sans | Local IBM Plex Sans |
| Header | Logo left, menu right | Centered brand row, separate console navigation |
| Footer | Brand plus three menu columns | CTA panel, horizontal resources, social and policy rows |
| ACF structure | Nested branding, hero, learning path and fixed cards | Mixed scalar fields, runtime group, metrics, visibility switch, color picker and workflow matrix |
| PHP organization | Procedural prefixed functions | Static theme runtime class |
| DOM/CSS namespace | `vibestart-*` | `devaccelerate-*` |

## Frontend Isolation

Each child theme:

- uses complete custom `header.php`, `footer.php`, and `front-page.php` templates;
- removes GeneratePress frontend CSS, JavaScript, dynamic CSS, body classes, and copyright output;
- uses unique script and style handles;
- removes the WordPress generator meta output;
- disables speculation rules that expose the parent theme path;
- loads only local WOFF2 font files;
- makes no requests to Google Fonts at runtime.

The required `Template: generatepress` declaration remains in each `style.css`; removing it would break WordPress child theme inheritance.

## ACF Local JSON

Each theme stores its field group in its own `acf-json` directory. ACF automatically loads the JSON belonging to the active theme.

Field structures can be maintained directly in JSON or synchronized into the ACF administration interface. Content values are stored on the single `Home` page and remain independent because every theme uses unique field names and keys.

## Verification

Run PHP syntax checks:

```powershell
Get-ChildItem .\wp-content\themes\vibestart-academy -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
Get-ChildItem .\wp-content\themes\devaccelerate-lab -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

Verify the page and active theme:

```powershell
wp post list --post_type=page --post_status=any --fields=ID,post_title,post_status,post_name
wp option get stylesheet
```

Verify menus:

```powershell
wp menu list --fields=term_id,name,count
wp menu location list
```

## Public Demo Sites

Replace the placeholders after deployment.

| Product | Public URL |
| --- | --- |
| VibeStart Academy | `TODO: add deployed URL` |
| DevAccelerate Lab | `TODO: add deployed URL` |

The public sites can be deployed with InstaWP or TasteWP. Deploy one complete site copy for each child theme and leave the corresponding theme active.
