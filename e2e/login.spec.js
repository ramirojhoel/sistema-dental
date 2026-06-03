import { test, expect } from '@playwright/test';

test('Login exitoso con credenciales válidas', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/login');

  await page.getByRole('textbox', { name: 'admin@dental.com' }).fill('admin@dental.com');
  await page.getByRole('textbox', { name: '••••••••' }).fill('123456');

  await page.getByRole('button', { name: 'Iniciar Sesión' }).click();

  await expect(page).not.toHaveURL('http://127.0.0.1:8000/login');
});

test('Login fallido con credenciales incorrectas', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/login');

  await page.getByRole('textbox', { name: 'admin@dental.com' }).fill('malo@dental.com');
  await page.getByRole('textbox', { name: '••••••••' }).fill('wrongpass');

  await page.getByRole('button', { name: 'Iniciar Sesión' }).click();

  await expect(page).toHaveURL('http://127.0.0.1:8000/login');
});
