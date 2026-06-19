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
  await page.getByRole('textbox', { name: 'Input keluhan kulit' }).click();
  await page.getByRole('button', { name: 'Cari produk' }).click();
});