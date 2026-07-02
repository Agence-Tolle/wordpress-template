import domReady from '@wordpress/dom-ready';
import { select, dispatch, subscribe } from '@wordpress/data';

/**
 * Corrige le mode "preview" forcé par ACF sur le premier bloc ajouté à une page vide.
 *
 * Sur une page vide, Gutenberg place le canvas de l'éditeur dans une iframe.
 * ACF détecte cette iframe au montage du bloc et force son attribut mode à
 * "preview" (champs dans la sidebar). L'iframe disparaît dès qu'un bloc ACF
 * est présent dans le contenu, mais le mode forcé reste jusqu'à un remontage
 * du bloc (ex. en le déplaçant). On remet donc le mode à "auto" une fois
 * l'iframe retirée.
 */
domReady(() => {
  const store = 'core/block-editor';

  const restoreAutoMode = (clientId, tries = 0) => {
    // L'iframe est encore là : on réessaie un peu plus tard. Si elle ne
    // disparaît jamais (éditeur de modèles, etc.), on abandonne.
    if (document.querySelector('iframe[name="editor-canvas"]')) {
      if (tries < 40) {
        setTimeout(() => restoreAutoMode(clientId, tries + 1), 50);
      }

      return;
    }

    const block = select(store).getBlock(clientId);

    // Seuls les blocs fraîchement insérés (data vide) sont corrigés, pour ne
    // pas écraser un mode "preview" choisi volontairement par l'utilisateur.
    if (
      block &&
      block.attributes.mode === 'preview' &&
      !Object.keys(block.attributes.data ?? {}).length
    ) {
      dispatch(store).updateBlockAttributes(clientId, { mode: 'auto' });
    }
  };

  let knownIds = new Set(select(store).getClientIdsWithDescendants());

  subscribe(() => {
    const currentIds = select(store).getClientIdsWithDescendants();

    currentIds.forEach((clientId) => {
      if (knownIds.has(clientId)) {
        return;
      }

      const name = select(store).getBlockName(clientId);

      if (name && name.startsWith('acf/')) {
        setTimeout(() => restoreAutoMode(clientId), 50);
      }
    });

    knownIds = new Set(currentIds);
  });
});
