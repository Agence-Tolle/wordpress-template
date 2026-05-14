# Retina Images Design

**Context**

The theme currently mixes custom image sizes declared in `app/setup.php` with hard-coded `sizes` attributes inside a few Blade block templates. That produces inconsistent behavior between custom blocks and standard WordPress content rendered through `the_content()`.

**Goal**

Build a shared Retina-ready image strategy for the whole theme so front-end images can request roughly `2x` the rendered width when an appropriately sized source exists, while still falling back cleanly to the largest available source.

**Recommended approach**

Use a centralized theme-level image policy:

- Define a coherent set of custom image sizes, including explicit Retina-oriented widths for the main theme use cases.
- Add global WordPress filters so standard content images inherit the same responsive logic.
- Replace block-level hard-coded image attributes with shared helpers so custom blocks follow the same rules as the rest of the site.

**Design**

1. Image formats

- Keep semantic widths for layout breakpoints and content widths.
- Add `2x` companion sizes for the widths used by the theme so WordPress can generate better `srcset` candidates.
- Keep unconstrained height cropping disabled to preserve current behavior.

2. Global rendering behavior

- Add theme helpers to generate consistent `sizes` strings based on the expected rendered width.
- Hook into global image rendering filters so images output through WordPress core can request a `2x` source through normal `srcset` selection rather than by forcing `full`.
- Prefer expressing the rendered slot width in the `sizes` attribute, leaving the browser free to choose the best candidate from `srcset`.

3. Block rendering behavior

- Update the custom image blocks that currently hard-code `sizes`.
- Use shared helpers instead of local string literals so block images and standard content use the same policy.

4. Fallback behavior

- If the uploaded original is smaller than the ideal `2x` target, WordPress should keep serving the best available candidate from the generated `srcset`.
- The theme should not force upscaling or introduce custom runtime resizing.

5. Validation

- Verify the new image size registry in code.
- Verify the global filters affect both attachment images and standard content output.
- Verify the custom blocks no longer carry divergent hard-coded values.
