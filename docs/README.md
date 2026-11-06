# Guía de Sistemas de Diseño y Estilos UI — TecnoFix Solutions (Sistema TSU GETI)

Este documento consolida toda la información técnica, visual y arquitectónica extraída del paquete de estilos `DiseñoInterfaces.zip` (`stitch_tecnofix_solutions_noir_interface`).

El paquete contiene **dos sistemas de diseño complementarios** (Modo Claro y Modo Oscuro/Noir) junto con **dos prototipos funcionales en HTML/Tailwind CSS** (Dashboard Multi-Rol y Portal Público).

---

## 📁 Estructura del Paquete Analizado

```text
stitch_tecnofix_solutions_noir_interface/
├── prussian_precision/
│   └── DESIGN.md                  # Especificación del Sistema de Diseño Claro (Minimalismo SaaS)
├── technical_noir_precision/
│   └── DESIGN.md                  # Especificación del Sistema de Diseño Oscuro (Industrial Noir)
├── tecnofix_solutions_dashboard_multi_rol_modo_claro/
│   ├── code.html                  # Implementación Dashboard Multi-Rol (Técnico, Recepción, Admin, Stock, Cliente)
│   └── screen.png                 # Captura de referencia visual del Dashboard
└── tecnofix_solutions_portal_p_blico_modo_claro/
    ├── code.html                  # Implementación Landing / Portal Público y Rastreo de Tickets
    └── screen.png                 # Captura de referencia visual del Portal Público
```

---

## 1. Sistema de Diseño 1: **Prussian Precision** (Modo Claro / SaaS Ejecutivo)

Diseñado para operaciones de alta densidad informativa sin fatiga visual. Inspirado en el rigor arquitectónico de **Linear** y el pulido editorial de **Stripe**. Prioriza planos estructurales planos, bordes de `1px` y elevación difusa hiper-sutil sin resplandores sintéticos (*Zero Glow Policy*).

### 1.1 Paleta de Colores (Prussian Precision)

Cumple estrictamente con **WCAG AA** (mínimo `4.5:1` para texto normal, `3:1` para elementos gráficos).

#### Colores Semánticos de Interfaz (UI Tokens)
| Rol / Token | Hexadecimal | Uso Principal |
| :--- | :--- | :--- |
| **Base Canvas** (`brand.canvas`) | `#F8FAFC` | Fondo general de aplicación (evita fatiga visual y separa las tarjetas blancas). |
| **Card & Container** (`brand.card`) | `#FFFFFF` | Superficie elevada para módulos, tarjetas, tablas y barras laterales. |
| **Borders & Dividers** (`brand.border`) | `#E2E8F0` | Líneas divisorias de `1px` para compartimentar sin ruido visual (`#F1F5F9` en divisiones internas). |
| **Primary Charcoal** (`brand.slate`) | `#0F172A` | Texto principal y encabezados de alta prioridad (contraste `15.8:1`). |
| **Secondary Slate** (`brand.muted`) | `#64748B` | Texto secundario, subtítulos, iconos inactivos y metadatos (contraste `4.6:1`). |
| **Placeholder / Muted** | `#94A3B8` | Placeholders de inputs y etiquetas deshabilitadas. |
| **Prussian Navy** (`brand.navy`) | `#14213D` | Color primario de marca, botones principales, estados activos de navegación (`#1E293B` o `#0E172A` en hover). |
| **Amber Accent** (`brand.amber`) | `#D97706` / `#F59E0B` | Alertas, contadores destacados, pasos de conversión primaria (`#D97706` en textos/bordes; `#F59E0B` en indicadores). |

#### Tokens Extendidos (Material 3 / Tema Base)
```yaml
surface: '#faf8ff'
surface-dim: '#d2d9f4'
surface-container-lowest: '#ffffff'
surface-container-low: '#f2f3ff'
surface-container: '#eaedff'
surface-container-high: '#e2e7ff'
surface-container-highest: '#dae2fd'
on-surface: '#131b2e'
on-surface-variant: '#45464d'
primary: '#000a24'
primary-container: '#14213d'
secondary: '#904d00'
secondary-container: '#fe932c'
error: '#ba1a1a'
error-container: '#ffdad6'
on-error-container: '#93000a'
```

#### Colores de Estados (Badges & Chips)
| Estado | Fondo | Texto | Borde (`1px solid`) |
| :--- | :--- | :--- | :--- |
| **Neutral / Info** | `#F1F5F9` | `#475569` | `#E2E8F0` |
| **Amber / Pendiente / En Proceso** | `#FEF3C7` | `#B45309` / `#92400E` | `#FDE68A` / `#FCD34D` |
| **Activo / Éxito / Listo** | `#ECFDF5` | `#047857` | `#A7F3D0` |
| **Error / Crítico** | `#FFDAD6` / `#FEF2F2` | `#93000A` / `#B91C1C` | `#FECACA` |

---

### 1.2 Tipografía (Prussian Precision)

El sistema combina la geometría editorial de **Josefin Sans** en encabezados con la legibilidad funcional de **Jost** en tablas, formularios y cuerpo (con variante **Inter + JetBrains Mono** en el Portal Público).

- **Regla numérica obligatoria:** `font-variant-numeric: tabular-nums` en todas las tablas analíticas, precios, métricas de SLA, tickets y contadores.
- **Tracking en encabezados:** `-0.02em` para tamaños superiores a `24px`.

| Token | Familia | Tamaño | Peso | Line Height |
| :--- | :--- | :--- | :--- | :--- |
| `display-lg` | Josefin Sans | `48px` (`32px` móvil) | `600` | `56px` (`40px` móvil) |
| `headline-lg` | Josefin Sans | `32px` (`24px` móvil) | `600` | `40px` (`32px` móvil) |
| `headline-md` | Josefin Sans | `24px` | `500` | `32px` |
| `headline-sm` | Josefin Sans | `20px` | `500` | `28px` |
| `title-md` | Jost | `16px` | `600` | `24px` |
| `body-lg` | Jost | `16px` | `400` | `24px` |
| `body-md` | Jost | `14px` | `400` | `20px` |
| `body-sm` | Jost | `12px` | `400` | `16px` |
| `label-md` | Jost | `13px` | `500` | `18px` |
| `label-sm` | Jost | `11px` | `600` | `14px` |

---

### 1.3 Layout, Espaciado, Bordes y Elevación (Prussian Precision)

- **Grid Base:** Escala de `8pt` con micro-escala de `4pt` para controles compactos.
- **Sistema de Columnas:** 12 columnas fluidas con ancho máximo de `1440px` (colapsa a 4 columnas en `<768px`).
- **Tokens de Espaciado:**
  - `space-xs`: `0.25rem` (`4px`)
  - `space-sm`: `0.5rem` (`8px`)
  - `space-md`: `1rem` (`16px`)
  - `space-lg` / `gutter`: `1.5rem` (`24px`)
  - `margin`: `2rem` (`32px`)
  - `space-xl`: `2.5rem` (`40px`)
- **Radios de Borde (`border-radius`):**
  - Controles, inputs, botones, tabs y chips: `6px` (`rounded-btn`)
  - Tarjetas, modales y paneles contenedores: `8px` (`rounded-card` / `0.5rem`)
  - Badges de código/estado compactos: `4px` (`0.25rem`)
  - Píldoras de estado y avatares: `9999px` (`rounded-full`)
- **Niveles de Sombra (Sin resplandor de color):**
  - **Nivel 0 (Base):** Borde sólido `1px #E2E8F0` sobre canvas `#F8FAFC`.
  - **Nivel 1 (Micro Shadow - Tarjetas/Botones):** `0 1px 2px 0 rgba(15, 23, 42, 0.04)`
  - **Nivel 2 (Float Shadow - Menús/Popovers):** `0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 2px 4px -2px rgba(15, 23, 42, 0.04)` + borde `1px #E2E8F0`.
  - **Nivel 3 (Overlay Shadow - Modales/Drawers):** `0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.03)` con backdrop `#0F172A` al `20%` de opacidad.

---

### 1.4 Especificación de Componentes (Prussian Precision)

- **Botones:**
  - **Primary:** Fondo `#14213D`, texto `#FFFFFF`, radio `6px`, altura `36px` (compacto) o `40px` (default). Hover: `#1E293B`. Active: `scale(0.99)`.
  - **Secondary / Outline:** Fondo `#FFFFFF`, borde `1px solid #E2E8F0`, texto `#0F172A`. Hover: fondo `#F8FAFC`, borde `#CBD5E1`.
  - **Ghost:** Transparente, texto `#64748B`. Hover: fondo `#F1F5F9`, texto `#0F172A`.
  - **Accent:** Fondo `#D97706`, texto `#FFFFFF` (exclusivo para conversiones críticas).
- **Inputs y Formularios:**
  - Altura `38px`, fondo `#FFFFFF`, borde `1px solid #E2E8F0`, radio `6px`, texto `14px #0F172A`, placeholder `#94A3B8`.
  - **Focus:** Transición de borde a `#14213D` con anillo exterior de `2px` en `rgba(20, 33, 61, 0.08)` (sin anillos azules neón).
  - **Checkboxes / Radios:** `16x16px`, borde `1px solid #CBD5E1`, radio `4px` (checkbox). Estado activo: relleno `#14213D` con check blanco.
- **Tarjetas (Cards):**
  - Fondo `#FFFFFF`, borde `1px solid #E2E8F0`, radio `8px`, padding `1.5rem` (`24px`). Header separado por `border-bottom: 1px solid #F1F5F9` con `padding-bottom: 1rem`.
- **Tablas de Datos:**
  - **Header Cell:** `label-sm` en Jost, mayúsculas (`uppercase`), tracking `0.05em`, color `#64748B`, fondo `#F8FAFC`, altura `36px`, `border-bottom: 1px solid #E2E8F0`.
  - **Row Cell:** `body-md` en Jost, color `#0F172A`, altura `44px`, `border-bottom: 1px solid #F1F5F9`. Hover: fondo `#F8FAFC`.

---

## 2. Sistema de Diseño 2: **Technical Noir Precision** (Modo Oscuro / Industrial)

Pensado para laboratorios de ingeniería electrónica, bahías de diagnóstico con baja iluminación y estaciones técnicas de alto rendimiento. Rechaza bordes redondeados y sombras difusas en favor de **geometría de ángulos rectos (`0px`)**, alto contraste mecánico y estética de instrumentación técnica.

### 2.1 Paleta de Colores (Technical Noir Precision)

#### Arquitectura Cromática Principal
| Rol / Token | Hexadecimal | Uso Principal |
| :--- | :--- | :--- |
| **Obsidian Core (Elevation 00)** | `#000000` | Sustrato base del lienzo, sidebar y contenedores profundos. |
| **Base Container (Elevation 01)** | `#080D18` | Contenedores base, módulos y cabeceras de tabla (Obsidiana con tinte Prusia). |
| **Card / Module (Elevation 02)** | `#0E172A` | Tarjetas elevadas, filas en hover, items activos de navegación y paneles flotantes. |
| **Prussian Blue (Elevation 03)** | `#14213D` | Superficies interactivas secundarias, cabeceras de modal y bordes estructurales base. |
| **Industrial Amber (Accent)** | `#FCA311` | Acento primario industrial (estados activos, focos de atención, indicadores en curso). |
| **Alabaster Gray** | `#E5E5E5` | Neutral técnico de alto contraste para encabezados, bordes activos y métricas. |
| **Pure White** | `#FFFFFF` | Texto focal primario y alertas diagnósticas de máximo contraste. |
| **Text Muted / Metadata** | `#7A889B` | Etiquetas secundarias, metadatos y cabeceras de columna. |
| **Structural Borders** | `#1F2E4D` / `#14213D` | Reglas divisorias por defecto (`#E5E5E5` o `#FCA311` en estado activo). |

#### Paleta Técnica de Estados de Reparación
| Estado del Equipo | Fondo | Borde (`1px solid`) | Texto | Indicador (`6px sq`) |
| :--- | :--- | :--- | :--- | :--- |
| **En diagnóstico** | `#14213D` / `rgba(20,33,61,0.8)` | `#3A506B` | `#E5E5E5` | `#7A889B` |
| **En reparación** | `#1E180D` / `rgba(252,163,17,0.12)` | `#FCA311` | `#FCA311` | `#FCA311` |
| **Esperando piezas** | `#200B0E` / `rgba(186,24,27,0.15)` | `#BA181B` | `#FF8FA3` | `#BA181B` |
| **Listo para entrega** | `#0C1A11` / `rgba(43,147,72,0.15)` | `#2B9348` | `#70E000` | `#2B9348` |

---

### 2.2 Tipografía (Technical Noir Precision)

Combina **Space Grotesk** para titulares geométricos, etiquetas en mayúsculas e identificadores con **Geist** para lectura de alta precisión en números de serie, IMEI, direcciones MAC y registros de diagnóstico.

| Token | Familia | Tamaño | Peso | Line Height | Letter Spacing |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `headline-xl` | Space Grotesk | `40px` (`30px` móvil) | `700` | `48px` (`38px` móvil) | `-0.02em` |
| `headline-lg` | Space Grotesk | `32px` (`24px` móvil) | `600` | `40px` (`32px` móvil) | `-0.01em` |
| `headline-md` | Space Grotesk | `24px` | `600` | `32px` | `0em` |
| `headline-sm` | Space Grotesk | `18px` | `600` | `26px` | `0.01em` |
| `body-lg` | Geist | `16px` | `400` | `24px` | `0em` |
| `body-md` | Geist | `14px` | `400` | `20px` | `0.005em` |
| `body-sm` | Geist | `12px` | `400` | `18px` | `0.01em` |
| `label-lg` | Space Grotesk | `14px` | `600` | `20px` | `0.05em` |
| `label-md` | Space Grotesk | `12px` | `500` | `16px` | `0.06em` |
| `label-sm` | Space Grotesk | `10px` | `600` | `14px` | `0.08em` |

---

### 2.3 Geometría, Elevación y Componentes (Technical Noir Precision)

- **Geometría Cortante (`border-radius: 0px`):** Ningún elemento tiene esquinas redondeadas. Opcionalmente se permite un chaflán mecánico a 45° (`6px` vía `clip-path`) en la esquina superior derecha de alertas o tarjetas prioritarias.
- **Cero Sombras Difusas:** Prohibido el uso de sombras con `blur`. Si un modal flotante requiere sombra proyectada, debe ser sólida y mecánica: `box-shadow: 4px 4px 0px 0px #000000` (o `6px 6px 0px #000000` en modales HUD).
- **Layout Responsivo:**
  - Desktop (`>= 1280px`): Grid de 12 columnas + Sidebar fija de `280px`.
  - Tablet (`768px - 1279px`): Grid de 8 columnas + Sidebar colapsada de iconos (`72px`).
  - Mobile (`< 768px`): Flujo de 4 columnas + barra de comandos superior.
- **Componentes Clave:**
  - **Botón Primario Industrial:** Relleno `#FCA311`, texto `#000000`, `Space Grotesk` bold en mayúsculas, radio `0px`. Hover: fondo `#FFFFFF`, texto `#000000`.
  - **Botón Secundario Noir:** Fondo `#14213D`, texto `#FFFFFF`, borde `1px solid #1F2E4D`. Hover: borde `#E5E5E5`, fondo `#1C2E56`.
  - **Sidebar Noir:** Fondo `#000000`, borde derecho `1px solid #14213D`. Item activo con fondo `#0E172A`, línea izquierda de `3px` en `#FCA311` y texto `#FFFFFF`.
  - **Tablas de Diagnóstico:** Filas con zebra-striping alternando `#000000` y `#05080F`, borde inferior `1px solid #0F1828`. Hover en fila: `#0E172A` con borde izquierdo `#FCA311`.
  - **Inputs:** Fondo `#000000`, borde `1px solid #1F2E4D`, texto `#FFFFFF`, placeholder `#49576E`. Focus instantáneo a borde `#FCA311` sin outline.

---

## 3. Implementación en los Prototipos HTML Incluidos

### 3.1 Dashboard Operativo Multi-Rol (`tecnofix_solutions_dashboard_multi_rol_modo_claro/code.html`)
Implementa el sistema **Prussian Precision** utilizando **Tailwind CSS**, fuentes `Jost` y `Josefin Sans`, e iconografía **Material Symbols Outlined**.
- **Configuración Tailwind personalizada:**
  ```javascript
  colors: {
    brand: {
      navy: "#14213d",
      amber: "#d97706",
      "amber-light": "#fef3c7",
      slate: "#0f172a",
      muted: "#64748b",
      border: "#e2e8f0",
      canvas: "#f8fafc",
      card: "#ffffff"
    }
  }
  ```
- **Arquitectura de Vistas:** Incluye un conmutador interactivo por rol (`switchRoleView`) en la barra lateral fija (`w-64`) que alterna dinámicamente entre 5 módulos:
  1. **Técnico (`tecnico`):** Gestión de bahía de taller, diagnóstico y reparación.
  2. **Recepción (`recepcion`):** Admisión de equipos y generación de órdenes.
  3. **Administración (`admin`):** Métricas operativas y cumplimiento de SLAs.
  4. **Almacén (`almacen`):** Control de inventario de refacciones y partes (`Stock`).
  5. **Portal Cliente (`cliente`):** Vista interna de seguimiento para clientes.

### 3.2 Portal Público y Rastreo (`tecnofix_solutions_portal_p_blico_modo_claro/code.html`)
Implementa la versión pública de **Prussian Precision** orientada a clientes corporativos, utilizando `Inter` para interfaz general y `JetBrains Mono` para códigos de ticket (`TFX-9842`), tiempos estimados (ETA) y especificaciones técnicas.
- **Secciones incluidas:**
  - **Header Sticky:** Navegación con `backdrop-blur-sm` (`bg-white/95`), enlaces a Servicios, Horarios, Ubicación, Políticas y modal de Inicio de Sesión.
  - **Hero & Rastreador en Tiempo Real:** Buscador de orden por folio con validación e interfaz de estado inmediata (`En Reparación • Bay 02`, ETA en fuente monoespaciada).
  - **Catálogo de Servicios, Ubicación de Laboratorio y Políticas de Garantía.**

---

## 4. Reglas de Oro para Desarrolladores (Quick Checklist)

1. **Consistencia Cromática:** El binomio de identidad de TecnoFix Solutions en ambos temas es **Prussian Navy (`#14213D`) + Industrial Amber (`#D97706` en claro / `#FCA311` en oscuro)**.
2. **Números Tabulares:** Siempre aplicar `tabular-nums` (`font-mono` o `font-variant-numeric: tabular-nums`) en folios de tickets, métricas, tiempos de SLA, precios y cantidades de inventario.
3. **Cero Resplandores (No Neon Glows):** Evitar sombras de colores brillantes o degradados decorativos. La jerarquía se construye con bordes de `1px` y contraste limpio de superficies.
4. **Iconografía:** Utilizar `Material Symbols Outlined` con trazos limpios (`wght 350-400`, sin relleno por defecto).
