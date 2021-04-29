$('.multiselect').multiselect({
    buttonWidth: '250',
    selectedList: 5,
    noneSelectedText: 'Добавить',
    selectedText: 'Выбрано # из #',
}).multiselectfilter({
    label: "Фильтр",
    placeholder: "Поиск",
    width: 150
});