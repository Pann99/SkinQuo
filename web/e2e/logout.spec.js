import { test, expect } from '@playwright/test';

test('LOGOUT-01 User berhasil logout', async ({ page }) => {

  await page.goto('http://127.0.0.1:8000/');

  await page.getByRole('link', { name: 'Login' }).click();

  await page.getByRole('textbox', {
    name: 'Email address'
  }).fill('lyrafaiqb@gmail.com');

  await page.getByRole('textbox', {
    name: 'Password'
  }).fill('#Tiaranac27');

  await page.getByRole('button', {
    name: 'Sign In'
  }).click();

  // Tunggu halaman selesai load
  await page.waitForLoadState('networkidle');

  // Arahkan mouse ke avatar agar menu logout muncul
  await page.getByRole('button', {
    name: /Avatar/i
  }).hover();

  // Tunggu tombol logout terlihat
  await expect(
    page.getByRole('button', { name: 'Logout' })
  ).toBeVisible();

  // Klik logout
  await page.getByRole('button', {
    name: 'Logout'
  }).click();

  // Tunggu redirect
  await page.waitForLoadState('networkidle');

});