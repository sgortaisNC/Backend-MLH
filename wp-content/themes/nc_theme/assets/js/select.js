const select = document.querySelectorAll('select');

select.forEach((item) => {
    NiceSelect.bind(item, {
        searchable:true,
        placeholder: 'Choisir une option',
        searchtext: 'Choisissez un mot-clé',
        selectedText: 'Élements sélectionnés',
    });
});

