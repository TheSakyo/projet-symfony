
/**
 * Permet d'effectuer un input select customisé au niveau de la selection des catégorie pour le formulaire des articles
 */
new Choices(document.querySelector('#article_form_tag'), { 
    
    removeItemButton: true, 
    loadingText: 'Chargement...',
    noResultsText: 'Aucun résultat trouvé',
    noChoicesText: 'Pas de choix possible',
    itemSelectText: 'Appuyez sur pour sélectionner',
    uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
    customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées',
    addItemText: (value) => { return `Appuyez sur la touche Entrée pour ajouter <b>"${value}"</b>`; },
    maxItemText: (maxItemCount) => { return `Seulement ${maxItemCount} peuvent être ajoutées`; }, 
});