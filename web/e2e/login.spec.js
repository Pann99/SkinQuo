import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/');
  await page.getByRole('link', { name: 'Login' }).click();
  await page.getByRole('textbox', { name: 'Email address' }).click();
  await page.getByRole('textbox', { name: 'Email address' }).fill('lyr');
  await page.getByRole('textbox', { name: 'Email address' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Email address' }).fill('lyr');
  await page.getByRole('textbox', { name: 'Email address' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Email address' }).fill('lyrafaiqahb@gmail.com');
  await page.getByRole('textbox', { name: 'Password' }).click();
  await page.getByRole('textbox', { name: 'Password' }).fill('#');
  await page.getByRole('textbox', { name: 'Password' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Password' }).fill('#L');
  await page.getByRole('textbox', { name: 'Password' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Password' }).fill('#Lyraf');
  await page.getByRole('button').first().click();
  await page.getByRole('textbox', { name: 'Password' }).click();
  await page.getByRole('textbox', { name: 'Password' }).fill('#Lyrafaiqah31');
  await page.getByRole('button', { name: 'Sign In' }).click();
  await page.goto('http://127.0.0.1:8000/profile');
});