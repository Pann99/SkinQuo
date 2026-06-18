import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/');
  await page.getByRole('link', { name: 'Login' }).click();
  await page.getByRole('link', { name: 'Forgot password?' }).click();
  await page.getByRole('textbox', { name: 'Email address' }).click();
  await page.getByRole('textbox', { name: 'Email address' }).fill('lyrafaiqb@gmail.com');
  await page.getByRole('button', { name: 'Send Reset Link' }).click();
  await page.goto('http://127.0.0.1:8000/forgot-password');
  await page.goto('http://127.0.0.1:8000/reset-password/b1781cec5808e2a6ed6c5347d5abed2071c6c9b1095bae349a56d20c3c98d284?email=lyrafaiqb%40gmail.com');
  await page.getByRole('textbox', { name: 'New Password' }).click();
  await page.getByRole('textbox', { name: 'New Password' }).click();
  await page.getByRole('textbox', { name: 'New Password' }).fill('#');
  await page.getByRole('textbox', { name: 'New Password' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'New Password' }).fill('#T');
  await page.getByRole('textbox', { name: 'New Password' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'New Password' }).fill('#Tiaranac31');
  await page.getByRole('button').first().click();
  await page.getByText('Confirm Password').click();
  await page.getByRole('textbox', { name: 'Confirm Password' }).fill('#');
  await page.getByRole('textbox', { name: 'Confirm Password' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Confirm Password' }).fill('#T');
  await page.getByRole('textbox', { name: 'Confirm Password' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Confirm Password' }).fill('#Tiaranac31');
  await page.getByRole('button').nth(1).click();
  await page.getByRole('button', { name: 'Reset Password' }).click();
  await page.goto('http://127.0.0.1:8000/login');
});