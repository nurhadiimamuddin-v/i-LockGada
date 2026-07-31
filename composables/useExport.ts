export const useExport = () => {
  const exportToCSV = (data, filename, columns) => {
    if (!data || !data.length) {
      alert('Tidak ada data untuk diekspor');
      return;
    }

    // Prepare header row
    const headers = columns.map(col => col.label).join(',');
    
    // Prepare data rows
    const rows = data.map(item => {
      return columns.map(col => {
        let val = item[col.key] || '';
        // Escape quotes and wrap in quotes if contains comma
        val = String(val).replace(/"/g, '""');
        if (val.includes(',')) {
          val = `"${val}"`;
        }
        return val;
      }).join(',');
    });

    const csvContent = [headers, ...rows].join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    
    // Trigger download
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `${filename}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  return { exportToCSV };
}
