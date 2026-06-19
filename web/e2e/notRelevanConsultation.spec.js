import { test, expect } from '@playwright/test';

test('CONSULTATION-02 Input tidak relevan ditolak oleh sistem', async ({ page }) => {
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

  // Isi query tidak relevan
  await page.getByRole('textbox', {
    name: 'Input keluhan kulit'
  }).fill('Cara memperbaiki mesin motor yang mogok di jalan');

  // Submit
  await page.getByRole('button', {
    name: 'Cari produk'
  }).click();

  // Tunggu sampai pesan error dari backend muncul
  await expect(
    page.locator('#analysisErrorMessage')
  ).toContainText(/Topik Tidak Dikenali/i, {
    timeout: 15000
  });

  // Pastikan halaman hasil TIDAK terbuka
  await expect(page).not.toHaveURL(
    /\/consultation\/\d+\/result/
  );

  // Pastikan tetap berada di halaman konsultasi
  await expect(page.locator('#screen-analysis')).toBeVisible();
});