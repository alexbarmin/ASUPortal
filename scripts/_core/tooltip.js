// Скрипт для инициализации элементов на странице, имеющих атрибут data-toggle="tooltip" (всплывающая подсказка)
$(function () {
  // инициализировать все элементы на страницы, имеющих атрибут data-toggle="tooltip", как компоненты tooltip
  $('[data-toggle="tooltip"]').tooltip()
})