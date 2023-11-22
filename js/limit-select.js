function shortString(selector) {
  const elements = document.querySelectorAll(selector);
  const tail = '...';
  if (elements && elements.length) {
    for (const element of elements) {
      let text = element.innerText;
      if (element.hasAttribute('data-limit')) {
        if (text.length > element.dataset.limit) {
          element.innerText = `${text.substring(0, element.dataset.limit - tail.length).trim()}${tail}`;
        }
      } else {
        throw Error('Cannot find attribute \'data-limit\'');
      }
    }
  }
}
