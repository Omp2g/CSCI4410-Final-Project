
import React, { useEffect } from 'react';
import { Html5QrcodeScanner } from 'html5-qrcode';

function QRScanner() {
  useEffect(() => {
    const scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });

    scanner.render((decodedText) => {
      try {
        const parsed = JSON.parse(decodedText);
        const token = localStorage.getItem('token');

        fetch('http://localhost/smart_attend/qr_attendance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          },
          body: JSON.stringify(parsed)
        })
        .then(res => res.json())
        .then(data => alert(data.message))
        .catch(err => console.error(err));
      } catch (err) {
        alert("Invalid QR Code");
      }
    });
  }, []);

  return (
    <div>
      <h2>Scan Attendance QR</h2>
      <div id="reader" style={{ width: "300px" }} />
    </div>
  );
}

export default QRScanner;