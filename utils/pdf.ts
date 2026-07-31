import jsPDF from 'jspdf'
import 'jspdf-autotable'

const generateSurat = (data, title, dateField) => {
  const doc = new jsPDF()

  // Logo (optional, since it's base64 in original we can add text or basic shape if we don't have base64)
  // For simplicity, we just add the Pegadaian Text
  doc.setFont('times', 'bold')
  doc.setFontSize(16)
  doc.text('SMART LOCKER PEGADAIAN', 105, 20, { align: 'center' })
  
  doc.setFontSize(12)
  doc.setFont('times', 'normal')
  if (data.lokasi_pegadaian) {
    doc.text(data.lokasi_pegadaian, 105, 26, { align: 'center' })
  }

  doc.setLineWidth(0.5)
  doc.line(20, 32, 190, 32)
  
  doc.setFontSize(14)
  doc.setFont('times', 'bold')
  doc.text(title, 105, 45, { align: 'center' })
  
  const today = new Date().toLocaleDateString('id-ID')
  const noSurat = `${data.nik_rahin || 'N/A'}/${data.kode_pegadaian || 'N/A'}/${data.id || 'N/A'}/${today}`
  
  doc.setFontSize(11)
  doc.setFont('times', 'normal')
  doc.text(`No: ${noSurat}`, 105, 52, { align: 'center' })

  doc.text('Yang bertanda tangan di bawah ini menerangkan bahwa:', 20, 65)

  const tableData = [
    ['Nama Nasabah', ':', data.nama_rahin || '-'],
    ['NIK Nasabah', ':', data.nik_rahin || '-'],
    ['Nomor Whatsapp', ':', data.no_whatsapp || '-'],
    ['Email', ':', data.email || '-'],
    ['Kode Locker', ':', data.kode_locker || '-'],
    [dateField.label, ':', data[dateField.key] || '-'],
    ['Deskripsi Barang', ':', data.deskripsi_barang || '-']
  ]

  doc.autoTable({
    startY: 70,
    body: tableData,
    theme: 'plain',
    styles: { font: 'times', fontSize: 11, cellPadding: 2 },
    columnStyles: {
      0: { cellWidth: 40 },
      1: { cellWidth: 5 },
      2: { cellWidth: 100 }
    }
  })

  const finalY = doc.lastAutoTable.finalY || 120

  doc.text('Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.', 20, finalY + 10)

  doc.text(`Jakarta, ${today}`, 140, finalY + 30)
  doc.text('Manager Cabang', 140, finalY + 35)

  doc.text('( ......................................... )', 140, finalY + 60)

  doc.save(`Surat_${data.nik_rahin}_${title.replace(/ /g, '_')}.pdf`)
}

export const cetakSuratGadai = (data) => {
  generateSurat(data, 'SURAT KETERANGAN BARANG DIGADAIKAN', { label: 'Tanggal Digadai', key: 'tgl_digadai' })
}

export const cetakSuratAmbil = (data) => {
  generateSurat(data, 'SURAT KETERANGAN BARANG DIAMBIL', { label: 'Tanggal Diambil', key: 'tgl_diterima' })
}
