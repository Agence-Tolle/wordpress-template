<div class="flex flex-col w-full h-full justify-center items-center bg-gray-50 p-4">
    <div class="border border-green-300 bg-white rounded-xl p-10 max-w-[750px] shadow-md">
        <h1 class="font-display text-48 md:text-74 font-bold text-[pink]">
            Bravo! 🎉
        </h1>

        <p class="text-xl text-green-300 mb-6">
            Ton installation locale est réussie! <span class="">(woop! woop!)</span>
        </p>

        <p class="text-lg text-gray-400">
            Voici ce qu'il te reste à faire:
        </p>

        <ol>
            <li>
                1. Update canIuse: copier et exécuter cette commande dans le terminal:
            </li>
            <li class="text-sm py-2 leading-[2]">
                <code class="bg-gray-200 p-2">npx update-browserslist-db@latest</code>
            </li>
            <li>
                2. Change le "Theme Name" dans le fichier style.css.
            </li>
            <li>
                3. Le "Text Domain" aussi tant qu'à être là.
            </li>
            <li>
                4. Installer les plugins qui se trouvent dans le dossier <span class="font-semibold">utilities/plugins</span> à la racine du thème.
            </li>
            <li>
                5. Générer les variables dans <span class="font-semibold">resources/css/_theme_variables.css</span>
            </li>
            <li>
                ... Importer les fichiers qui sont dans <span class="font-semibold">resources/acf-json</span> avec l'outil d'import de ACF.
            </li>
            <li>
                ... Retirer la ligne 9 de <span class="font-semibold">index.blade.php</span> (cette intro!)
            </li>
        </ul>

        <h2 class="font-display text-48 text-[pink] mt-10">
            Bonne chance!
        </h2>
    </div>
</div>