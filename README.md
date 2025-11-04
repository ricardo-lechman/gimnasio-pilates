# 🧘‍♀️ Sistema de Reservas y Pagos - Gimnasio de Pilates

Aplicación web desarrollada con **Laravel 11**, **Jetstream**, **Inertia.js** y **Vue 3** para la gestión integral de turnos, pagos y usuarios en un gimnasio de pilates.  
Incluye paneles diferenciados para **administradores** y **usuarios**, control de acceso, integración de pagos, y una interfaz moderna y responsiva.

---

## 🚀 Características principales

- 🔐 **Autenticación completa** (login, registro, recuperación de contraseña) con Jetstream, Fortify y Sanctum.  
- 🧑‍💻 **Panel de administración** con vistas separadas para:
  - Usuarios
  - Reservas
  - Pagos
  - Configuración
  - Estadísticas  
- 🙋‍♀️ **Panel de usuario** con:
  - Perfil editable
  - Agenda de turnos
  - Cancelación de reservas con 24 h de anticipación
  - Carga de comprobante de pago (imagen o PDF)
- 💳 **Integración con Mercado Pago** (opcional, vía comprobante o API)
- 🗓️ **Gestión de turnos**: 4 camas, 8 turnos diarios de 1h30 min.
- 📂 **Subida segura de archivos** (comprobantes de pago) almacenados en `/storage/app/public/comprobantes`.
- 📱 **Diseño 100 % responsivo** con Tailwind CSS.

---

## 🧩 Tecnologías utilizadas

| Tecnología | Uso principal |
|-------------|----------------|
| **Laravel 11** | Backend y lógica de negocio |
| **Jetstream + Fortify + Sanctum** | Autenticación y control de sesiones |
| **Inertia.js** | Comunicación entre Laravel y Vue |
| **Vue 3 + Composition API** | Frontend reactivo |
| **Tailwind CSS** | Estilos modernos y responsivos |
| **MySQL** | Base de datos relacional |
| **Vite** | Bundler y servidor de desarrollo |
| **Mercado Pago API** | Gestión de pagos (opcional) |

---

## ⚙️ Instalación y configuración

### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/tuusuario/nombre-del-proyecto.git
cd nombre-del-proyecto

