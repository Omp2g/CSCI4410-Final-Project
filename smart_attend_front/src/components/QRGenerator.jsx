
import React from 'react';
import { QRCodeCanvas } from 'qrcode.react';

function QRGenerator({ classId }) {
  const today = new Date().toISOString().split('T')[0];
  const qrData = JSON.stringify({ class_id: classId, date: today });

  return (
    <div>
      <h2>QR Code for Today’s Class</h2>
      <QRCodeCanvas value={qrData} size={256} />
    </div>
  );
}

export default QRGenerator;