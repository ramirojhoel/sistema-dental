import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('http://127.0.0.1:8000/login');
  await page.getByRole('textbox', { name: 'admin@dental.com' }).fill('admin@dental.com');
  await page.getByRole('textbox', { name: '••••••••' }).fill('123456');
  await page.getByRole('button', { name: 'Iniciar Sesión' }).click();
}

test('Crear nuevo paciente', async ({ page }) => {
  await login(page);

  await page.getByRole('link', { name: '👥 Pacientes' }).click();
  await page.getByRole('link', { name: '+ Nuevo Paciente' }).click();

  // Usamos un CI único basado en timestamp para evitar duplicados
  const ci = Date.now().toString().slice(-8);

  await page.getByRole('textbox', { name: 'Ej: Juan' }).fill('Hiromi');
  await page.getByRole('textbox', { name: 'Ej: Pérez' }).fill('Higuruma');
  await page.getByRole('textbox', { name: 'Ej: 12345678' }).fill(ci);
  await page.getByRole('textbox', { name: 'Ej: 77712345' }).fill('71712666');
  await page.getByRole('textbox', { name: 'Ej: Av. Heroínas #123,' }).fill('Av. America y Tupuraya');
  await page.getByRole('combobox').selectOption('M');
  await page.locator('input[name="date_of_birth"]').fill('1993-03-03');
  await page.getByRole('textbox', { name: 'Ej: María Pérez' }).fill('Kenjaku');
  await page.getByRole('textbox', { name: 'Ej: 77798765' }).fill('71643205');
  await page.getByRole('button', { name: '👥 Registrar Paciente' }).click();

  // Verificar que redirigió (ya no está en /create)
  await expect(page).not.toHaveURL('http://127.0.0.1:8000/patients/create');
});

test('Editar paciente existente', async ({ page }) => {
  await login(page);

  await page.getByRole('link', { name: '👥 Pacientes' }).click();
  await page.getByRole('link', { name: 'Editar' }).first().click();

  await page.locator('input[name="first_name"]').fill('Higuruma');
  await page.locator('input[name="last_name"]').fill('Hiromi');
  await page.locator('input[name="phone_number"]').fill('71712667');
  await page.locator('input[name="emergency_contact_name"]').fill('Kenjaku Geto');

  await page.getByRole('button', { name: '💾 Guardar Cambios' }).click();

  await expect(page).not.toHaveURL(/edit/);
});
