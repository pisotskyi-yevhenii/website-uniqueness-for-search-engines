# Part 1 — Decision Document

https://drive.google.com/drive/folders/152H3whbn_wHbFGXimfqkynqGHCsdmJ3x?usp=drive_link

# Part 2 — Practical Implementation

## GeneratePress Child Theme Uniqueness Demo

This repository contains two independent GeneratePress child themes created for the practical part of the Middle WordPress Full-Stack test assignment.

Both themes use the same educational domain but target different audiences:

- **DevAccelerate Lab** teaches working developers how to select, constrain, review, and integrate AI tools into professional development workflows.
- **VibeStart Academy** teaches complete beginners how to build simple projects with AI tools without a traditional coding background.

Links to 2 websites

1) **DevAccelerate Lab**
	https://fast-team.s6-tastewp.com
	https://fast-team.s6-tastewp.com/wp-admin/
	
	U: admin-ai
	P: admin-ai

2) **VibeStart Academy**	
	https://vibestartacadem.s2-tastewp.com
	https://vibestartacadem.s2-tastewp.com/wp-admin/
	
	U: admin-ai
	P: admin-ai

### Requirements

- WordPress 7.0 or later
- PHP 8.3 or later
- GeneratePress 3.6.1 or later
- Advanced Custom Fields Free 6.8.4 or later


### Installation

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

### Content Editing

All visible site content is editable from the WordPress admin.

