
export const openPrintWindow = (results, options = {}) => {
  const miscOptions = options?.misc || '';
  const orientation = 'landscape';
  const defaultX = options?.height || '800';
  const defaultY = options?.width || '1024';

  const height = orientation === 'potrait' ? defaultY : defaultX;
  const width = orientation === 'potrait' ? defaultX : defaultY;

  const w = window.open(
    window.location.href,
    '_blank',
    `height=${height},width=${width},resizable=yes,scrollbars=yes,toolbar=yes,autoHideMenuBar=true,location=yes${miscOptions}`,
  );

  w.document.open();
  w.document.write(results.toString());
  w.document.close();

  setTimeout(() => {
    w.window.print();
  }, 1000);
};

export default {
  openPrintWindow,
};
