import { test, expect } from '@playwright/test';

test('CONSULTATION-01 User berhasil mendapatkan rekomendasi produk', async ({ page }) => {

  test.setTimeout(180000);

  // Login
  await page.goto('http://127.0.0.1:8000/');

  await page.getByRole('link', {
    name: 'Login'
  }).click();

  await page.getByRole('textbox', {
    name: 'Email address'
  }).fill('lyrafaiqb@gmail.com');

  await page.getByRole('textbox', {
    name: 'Password'
  }).fill('#Tiaranac31');

  await page.getByRole('button', {
    name: 'Sign In'
  }).click();

  // Tunggu login selesai
  await page.waitForTimeout(5000);

  console.log('URL setelah login:', await page.url());

  // Buka halaman konsultasi
  await page.goto('http://127.0.0.1:8000/consultation');

  // Isi keluhan
  await page.locator('.sq-input-box').click();

  await page.getByRole('textbox', {
    name: 'Input keluhan kulit'
  }).fill(`
Kulit saya kombinasi cenderung berminyak di area T-zone dan kering di bagian pipi. Saya memiliki masalah jerawat aktif, bekas jerawat kemerahan, komedo hitam di hidung, pori-pori besar, tekstur kulit tidak merata, serta kulit yang mudah kusam setelah beraktivitas seharian. Saya sedang mencari serum yang mengandung niacinamide, salicylic acid, centella asiatica, hyaluronic acid, dan bahan lain yang dapat membantu mengurangi jerawat, mengontrol minyak, memperbaiki skin barrier, mencerahkan wajah, menyamarkan bekas jerawat, mengecilkan tampilan pori-pori, dan memberikan hidrasi yang cukup dengan budget sekitar seratus ribu rupiah sampai seratus lima puluh ribu rupiah.
`);

  // Submit konsultasi
  await page.getByRole('button', {
    name: 'Cari produk'
  }).click();

  console.log('Menunggu proses AI selesai...');

  // Tunggu redirect otomatis ke halaman hasil
  await page.waitForURL(
    /\/consultation\/\d+\/result/,
    {
      timeout: 120000
    }
  );

  console.log('URL hasil:', await page.url());

  // Verifikasi berhasil masuk halaman hasil
  await expect(page).toHaveURL(
    /\/consultation\/\d+\/result/
  );

});