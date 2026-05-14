# Retina Images Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a shared Retina-ready image foundation so theme blocks and standard WordPress content can request image candidates up to roughly `2x` the rendered width through consistent `srcset` and `sizes` behavior.

**Architecture:** The change will centralize image policy in the theme. `app/setup.php` will own the image size registry plus shared helper functions and WordPress filters for responsive image attributes, while Blade blocks that currently hard-code `sizes` will switch to the shared policy. The implementation will rely on WordPress responsive image selection instead of forcing `full` images, preserving graceful fallback to the best available source.

**Tech Stack:** WordPress theme PHP, Sage/Blade templates, WordPress responsive image filters, existing theme image sizes

---

### Task 1: Add a failing regression test for shared Retina image policy

**Files:**
- Create: `wp-content/themes/wordpress-template/tests/Feature/RetinaImagesTest.php`
- Inspect: `wp-content/themes/wordpress-template/phpunit.xml.dist` if present, otherwise existing test bootstrap files

**Step 1: Write the failing test**

Add a feature test that asserts:

- the theme registers Retina-oriented image sizes
- the shared helper can build a `sizes` string for a full-width slot and a half-width/content slot
- the global image attribute filter applies a shared `sizes` value when none is explicitly passed

**Step 2: Run test to verify it fails**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: FAIL because the helper/filter/image sizes do not exist yet.

**Step 3: Write minimal implementation**

Implement only the minimum image registry and helper/filter plumbing needed to satisfy the assertions.

**Step 4: Run test to verify it passes**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: PASS

**Step 5: Commit**

```bash
git add tests/Feature/RetinaImagesTest.php app/setup.php
git commit -m "feat: add shared retina image policy"
```

### Task 2: Expand the theme image size registry

**Files:**
- Modify: `wp-content/themes/wordpress-template/app/setup.php`
- Inspect: current image size definitions in the same file

**Step 1: Write the failing test**

Extend the previous test or add a dedicated assertion list for the expected size names and widths.

**Step 2: Run test to verify it fails**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: FAIL because the new size names are not registered yet.

**Step 3: Write minimal implementation**

Replace the current ad hoc image sizes with a clearer registry that includes the main widths and their `2x` companions, keeping non-cropped behavior.

**Step 4: Run test to verify it passes**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: PASS

**Step 5: Commit**

```bash
git add app/setup.php tests/Feature/RetinaImagesTest.php
git commit -m "feat: standardize retina image sizes"
```

### Task 3: Add global WordPress filters for responsive image attributes

**Files:**
- Modify: `wp-content/themes/wordpress-template/app/setup.php`
- Inspect: `wp-content/themes/wordpress-template/resources/views/partials/content-page.blade.php`
- Inspect: `wp-content/themes/wordpress-template/resources/views/partials/content-single.blade.php`

**Step 1: Write the failing test**

Add assertions that the theme-level image attribute filter returns shared `sizes` values for attachment images when the caller did not already define one.

**Step 2: Run test to verify it fails**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: FAIL because the filter is not active yet.

**Step 3: Write minimal implementation**

Add theme helpers and filters such as:

- a helper to resolve semantic image contexts into `sizes` strings
- `wp_get_attachment_image_attributes` filter to supply default `sizes`
- optional `wp_calculate_image_sizes` filter if needed to normalize content images emitted by core

Preserve explicit `sizes` values passed by templates so local overrides remain possible.

**Step 4: Run test to verify it passes**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: PASS

**Step 5: Commit**

```bash
git add app/setup.php tests/Feature/RetinaImagesTest.php
git commit -m "feat: add global retina image attribute filters"
```

### Task 4: Refactor custom blocks to use the shared image policy

**Files:**
- Modify: `wp-content/themes/wordpress-template/resources/views/blocks/base-header-home.blade.php`
- Modify: `wp-content/themes/wordpress-template/resources/views/blocks/base-header-page.blade.php`
- Modify: `wp-content/themes/wordpress-template/resources/views/blocks/base-text-image.blade.php`
- Modify: `wp-content/themes/wordpress-template/resources/views/components/buttons.blade.php` if icon sizes are part of the same shared helper usage

**Step 1: Write the failing test**

Add a rendering-level assertion if the test suite supports template rendering; otherwise add a focused snapshot/string assertion that these templates no longer use hard-coded `sizes` literals.

**Step 2: Run test to verify it fails**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: FAIL because the templates still embed local `sizes` strings.

**Step 3: Write minimal implementation**

Replace hard-coded `sizes` strings with calls to the shared helper and choose the correct semantic slot per block:

- full-bleed hero/header images
- half-width text-image media column
- small decorative/icon images only if appropriate

**Step 4: Run test to verify it passes**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: PASS

**Step 5: Commit**

```bash
git add resources/views/blocks/base-header-home.blade.php resources/views/blocks/base-header-page.blade.php resources/views/blocks/base-text-image.blade.php resources/views/components/buttons.blade.php tests/Feature/RetinaImagesTest.php app/setup.php
git commit -m "refactor: align block images with retina policy"
```

### Task 5: Verify the integrated behavior

**Files:**
- Modify: none unless verification reveals a defect

**Step 1: Run targeted test suite**

Run: `vendor/bin/phpunit --filter RetinaImagesTest`
Expected: PASS

**Step 2: Run broader validation**

Run: `vendor/bin/phpunit`
Expected: PASS, or document any unrelated failing tests already present.

**Step 3: Sanity-check theme templates**

Run: `rg -n \"sizes\\s*=>|wp_get_attachment_image\\(\" resources/views`
Expected: shared helper usage in the updated blocks and no divergent hard-coded `sizes` strings in the targeted files.

**Step 4: Commit**

```bash
git add .
git commit -m "test: verify retina image foundation"
```
