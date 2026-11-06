# 🛠️ TecnoFix Solutions | SoporTec v4.2
## Sistema de Diseño: Technical Noir Workshop

Documentación integral del sistema de diseño, arquitectura visual, paleta de colores, tipografía, cuadrículas y componentes para la plataforma de gestión de reparaciones **SoporTec v4.2** de **TecnoFix Solutions**.

---

## 1. Filosofía y Concepto Visual

El sistema de diseño **Technical Noir Workshop** traslada la tensión cinematográfica, el claroscuro y la precisión geométrica del *Film Noir* a un entorno de instrumentación técnica e industrial para el diagnóstico y reparación de hardware informático.

El concepto emula un **banco de trabajo quirúrgico en penumbra**, donde la información crítica y las telemetrías cobran vida mediante indicadores ámbar de advertencia y pantallas de alta precisión técnica:

* **Claroscuro Cinemático:** Contraste absoluto entre fondos negros puros (`#000000`), paneles en azul pizarra prusiano (`#14213d`) y texto nítido en alabastro (`#e5e5e5`), reservando el ámbar industrial (`#fca311`) para focos de acción y telemetría.
* **Rigor Arquitectónico:** La forma sigue al plano esquemático. Micro-bordes de `1px` dividen limpiamente matrices de hardware, colas de servicio y terminales de depuración.
* **Utilitarismo Industrial:** Sin brillos redondeados ni gradientes saturados innecesarios. Esquinas vivas (`border-radius: 0px`) que comunican mecanizado de precisión, bordes de carcasa de aluminio y circuitos impresos (PCB).

---

## 2. Paleta Cromática y Tokens

### 2.1 Colores Principales (Noir Exact Palette)

| Token | Código HEX | Rol y Aplicación |
| :--- | :--- | :--- |
| `noir-black` | `#000000` | Fondo general en modo oscuro, campos de texto y contraste extremo |
| `noir-blue` | `#14213d` | Superficie de contenedores elevados, cabeceras de tablas y tarjetas |
| `noir-amber` | `#fca311` | Foco de acción interactivo, botón primario, advertencias y telemetría |
| `noir-grey` | `#e5e5e5` | Texto principal, bordes de 1px arquitectónicos y etiquetas |
| `noir-white` | `#ffffff` | Texto destacado, números de serie (IMEI/MAC) y valores críticos |

### 2.2 Escala de Superficies (Material / Industrial Tokens)

```yaml
surface: '#131313'
surface-container-lowest: '#0e0e0e'
surface-container-low: '#1b1b1b'
surface-container: '#1f1f1f'
surface-container-high: '#2a2a2a'
surface-container-highest: '#353535'
outline: '#a18e79'
outline-variant: '#534433' (ó rgba(229, 229, 229, 0.12))
primary-container: '#fca311'
secondary-container: '#3c4967'
error: '#ffb4ab'
error-container: '#93000a'
```

### 2.3 Modos de Visualización

1. **Modo Oscuro (Default de Taller):** 
   - Fondo: `#000000` o `#131313`.
   - Contenedores: `#0e0e0e` a `#14213d`.
   - Micro-bordes: `1px solid rgba(229, 229, 229, 0.12)` o `#534433`.
   - Tipografía: `#e2e2e2` y `#ffffff`.
2. **Modo Claro (Inversión Diagnóstica):**
   - Diseñado para terminales en mostrador de luz natural o impresión de comprobantes.
   - Fondo: `#ffffff`.
   - Contenedores: `#f4f4f5` y `#e5e5e5`.
   - El ámbar se ajusta a `#d48207` para preservar contraste AA/AAA.

### 2.4 Acentos Funcionales por Rol del Sistema

* **Administrador:** Marco de alto contraste en alabastro/blanco con soporte azul prusiano y panel de ingresos globales.
* **Recepción:** Distintivos azul prusiano con etiquetas técnicas blancas; vista optimizada para registro rápido y ticket térmico.
* **Técnico:** Foco de borde ámbar industrial (`#fca311`), listas de diagnóstico paso a paso y solicitud de repuestos/herramientas.
* **Almacén:** Tonalidades pizarra/acero para validación de SKUs, anaqueles e inventario crítico.
* **Cliente:** Monocromo de alta legibilidad, barra de progreso secuencial en bloques y botones ámbar para aprobar presupuestos.

---

## 3. Tipografía y Jerarquía

La combinación tipográfica reproduce la instrumentación de laboratorio y la lectura técnica prolongada:

```html
<!-- Google Fonts CDN -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Josefin+Sans:wght@600;700&family=Space+Grotesk:wght@500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
```

### 3.1 Asignación Tipográfica

| Familia Tipográfica | Token de Clase | Función en la Plataforma |
| :--- | :--- | :--- |
| **Space Grotesk** | `font-headline-*` | Títulos principales, códigos de módulo y marcas industriales |
| **Hanken Grotesk** | `font-body-*` | Párrafos, descripciones de fallas, notas diagnósticas y políticas |
| **JetBrains Mono** | `font-label-*` / `.table-cell-mono` | Serials, IMEIs, códigos de orden (`ST-2026-0001`), precios, fechas y telemetría |
| **Josefin Sans** | `font-josefin` | Emblema y monograma de marca (`SF`) |

### 3.2 Escala de Fuentes

* **`headline-xl`**: `40px` (Desktop) / `30px` (Móvil), `700`, interlineado `48px`, tracking `-0.02em`.
* **`headline-lg`**: `28px` (Desktop) / `22px` (Móvil), `600`, interlineado `36px`, tracking `-0.01em`.
* **`headline-md`**: `20px`, `600`, interlineado `28px`.
* **`body-lg`**: `16px`, `400`, interlineado `24px`.
* **`body-md`**: `14px`, `400`, interlineado `20px`.
* **`body-sm`**: `12px`, `400`, interlineado `18px`.
* **`label-lg`**: `14px`, `600`, interlineado `20px`, tracking `0.04em` (Monospace).
* **`label-md`**: `12px`, `500`, interlineado `16px`, tracking `0.05em` (Monospace).
* **`label-sm`**: `10px`, `500`, interlineado `14px`, tracking `0.08em` (Monospace).

---

## 4. Cuadrícula, Espaciado y Profundidad

### 4.1 Rejilla Responsiva (Grid)

* **Desktop (≥ 1280px):** Rejilla de 12 columnas, margen `2.5rem`, separación (*gutter*) `1.5rem`. Admite paneles de banco dividido (Cola de tickets \| Telemetría \| Lista de verificación \| Despacho).
* **Tablet (768px - 1279px):** Rejilla de 8 columnas, margen `1.5rem`, separación `1rem`. Barra lateral colapsable con navegación persistente.
* **Móvil (< 768px):** Rejilla de 4 columnas, margen `1rem`, separación `1rem`. Pila vertical con barra de acciones inferior persistente para escaneo de códigos de barra.

### 4.2 Fondo de Cuadrícula Técnica (`film-grid`)

```css
.film-grid {
  background-size: 24px 24px;
  background-image: 
    linear-gradient(to right, rgba(229, 229, 229, 0.03) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(229, 229, 229, 0.03) 1px, transparent 1px);
}
```

### 4.3 Sombras Estructurales (Zero-Spread Occlusions)

En el sistema Noir no existen sombras difusas o desenfocadas. Se usan oclusiones duras y resplandores discretos:
* **Tarjeta Noir:** `box-shadow: 0 4px 0px 0px rgba(0, 0, 0, 0.85);`
* **Glow Ámbar de Telemetría:** `box-shadow: 0 0 10px rgba(252, 163, 17, 0.28);`
* **Línea de Enfoque:** `border-top: 2px solid #fca311;`

---

## 5. Componentes Principales

### 5.1 Botones (`0px` Border-Radius)
* **Primario (Ámbar Industrial):** Fondo `#fca311`, texto negro `#000000`, mayúsculas en monospace negrita. Al pasar el cursor (*hover*), el fondo conmuta a `#ffffff`.
* **Secundario (Azul Prusiano):** Fondo `#14213d`, borde de `1px solid #e5e5e5`, texto blanco `#ffffff`.
* **Fantasma / Wireframe:** Fondo transparente, borde `1px solid rgba(229, 229, 229, 0.25)`, texto `#e5e5e5`. Al hover borde y texto pasan a `#fca311`.

### 5.2 Badges y Estados Técnicos
* **`Pendiente / En Cola`**: Borde `#e5e5e5`, fondo transparente, texto `#e5e5e5`.
* **`En Diagnóstico / En Proceso`**: Borde `#14213d`, fondo `#14213d`, texto `#ffffff`.
* **`Esperando Repuesto`**: Borde `#fca311`, fondo `rgba(252, 163, 17, 0.08)`, texto `#fca311`.
* **`Terminado / QA`**: Borde `#ffffff`, fondo `#ffffff`, texto `#000000`.
* **`Crítico / Cancelado`**: Borde `#d90429`, fondo `rgba(217, 4, 41, 0.1)`, texto `#ffb4ab`.

### 5.3 Comprobante Térmico (`thermal-receipt`)
Diseño emulado de papel térmico para tickets de ingreso y factura de entrega:
```css
.thermal-receipt {
  background: #fdfbf7;
  color: #111111;
  font-family: 'JetBrains Mono', monospace;
  box-shadow: 0 8px 16px rgba(0,0,0,0.4);
}
```

---

## 6. Arquitectura de Plantillas Blade

* **`resources/views/layouts/public.blade.php`**: Plantilla para el portal público, seguimiento de estado de equipos mediante código de ticket o número de serie, horarios del taller, ubicación y políticas de garantía.
* **`resources/views/layouts/private.blade.php`**: Plantilla de banco de trabajo técnico para roles internos (Administrador, Recepción, Técnico, Almacén) y clientes autenticados, con sidebar estructurado, telemetría y barra de herramientas fija.

