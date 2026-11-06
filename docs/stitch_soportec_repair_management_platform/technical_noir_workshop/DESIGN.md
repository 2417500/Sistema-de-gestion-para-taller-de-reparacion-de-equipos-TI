---
name: Technical Noir Workshop
colors:
  surface: '#131313'
  surface-dim: '#131313'
  surface-bright: '#393939'
  surface-container-lowest: '#0e0e0e'
  surface-container-low: '#1b1b1b'
  surface-container: '#1f1f1f'
  surface-container-high: '#2a2a2a'
  surface-container-highest: '#353535'
  on-surface: '#e2e2e2'
  on-surface-variant: '#d9c3ad'
  inverse-surface: '#e2e2e2'
  inverse-on-surface: '#303030'
  outline: '#a18e79'
  outline-variant: '#534433'
  surface-tint: '#ffb95f'
  primary: '#ffc887'
  on-primary: '#472a00'
  primary-container: '#fca311'
  on-primary-container: '#663f00'
  inverse-primary: '#855300'
  secondary: '#b9c6ea'
  on-secondary: '#23304d'
  secondary-container: '#3c4967'
  on-secondary-container: '#abb8db'
  tertiary: '#d1d2d2'
  on-tertiary: '#2f3131'
  tertiary-container: '#b6b6b6'
  on-tertiary-container: '#464848'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffddb8'
  primary-fixed-dim: '#ffb95f'
  on-primary-fixed: '#2a1700'
  on-primary-fixed-variant: '#653e00'
  secondary-fixed: '#d9e2ff'
  secondary-fixed-dim: '#b9c6ea'
  on-secondary-fixed: '#0d1b36'
  on-secondary-fixed-variant: '#3a4664'
  tertiary-fixed: '#e2e2e2'
  tertiary-fixed-dim: '#c6c6c6'
  on-tertiary-fixed: '#1a1c1c'
  on-tertiary-fixed-variant: '#454747'
  background: '#131313'
  on-background: '#e2e2e2'
  surface-variant: '#353535'
typography:
  headline-xl:
    fontFamily: Space Grotesk
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-xl-mobile:
    fontFamily: Space Grotesk
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Space Grotesk
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Space Grotesk
    fontSize: 22px
    fontWeight: '600'
    lineHeight: 30px
    letterSpacing: 0em
  headline-md:
    fontFamily: Space Grotesk
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Hanken Grotesk
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Hanken Grotesk
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 18px
  label-lg:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.04em
  label-md:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: JetBrains Mono
    fontSize: 10px
    fontWeight: '500'
    lineHeight: 14px
    letterSpacing: 0.08em
spacing:
  gutter: 1rem
  gutter-desktop: 1.5rem
  margin: 1rem
  margin-tablet: 1.5rem
  margin-desktop: 2.5rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style

This design system translates the cinematic tension, precision, and structural shadows of Film Noir into an industrial diagnostic environment for IT hardware diagnostics, telemetry, and repair lifecycle management. The interface evokes a surgical workbench illuminated by amber diagnostic indicators in a darkened laboratory. 

Targeting bench technicians, logistics handlers, service managers, and enterprise clients, the aesthetic communicates mechanical discipline, diagnostic certainty, and zero tolerance for visual noise.

Key tenets:
- **Cinematic Chiaroscuro:** Deep blacks, structural deep prussian blues, and alabaster accents form a high-contrast foundation where critical data glows in amber warning states.
- **Architectural Rigor:** Form follows blueprint. Crisp micro-borders delineate dense hardware diagnostic matrices, ticket queues, and telemetry streams.
- **Industrial Utilitarianism:** Strictly devoid of skeuomorphic gloss, playful emojis, or neon saturation. Visual priority is dictated by diagnostic status, equipment serial markers, and real-time state changes.

## Colors

The palette leverages high-contrast chiaroscuro with an active industrial amber signal tone.

### Palette Architecture
- **Primary (`#fca311` Amber / Safety Industrial):** Reserved for interactive focal points, active hardware alert statuses, state confirmations, and critical diagnostic telemetry.
- **Secondary (`#14213d` Prussian Blue / Midnight Slate):** Serves as elevated containment surfaces, table header backgrounds, terminal panels, and grouped workbench modules.
- **Tertiary (`#e5e5e5` Alabaster Light):** The primary typographic and border vehicle in dark mode; provides architectural crispness and immediate readability.
- **Neutral (`#000000` True Noir Pitch):** Grounding canvas foundation in dark mode. Absolute black eliminates visual blooming on workbench displays.
- **Pure White (`#ffffff`):** Emphasized high-priority data points, serial IDs, and primary reading paths.

### Mode Mapping & Semantics
- **Dark Mode (Default):** Canvas sits at `#000000`, card and module containers utilize `#080d1a` to `#14213d`, structural borders render at `rgba(229, 229, 229, 0.15)` or `#14213d`, and typography alternates between `#ffffff` and `#e5e5e5`.
- **Light Mode (Diagnostic Inversion):** Canvas sits at `#ffffff`, surfaces at `#f4f4f5` and `#e5e5e5`, structural borders transition to `#14213d` at 20% opacity, text reads in `#000000` and `#14213d`, and `#fca311` shifts to `#d48207` to maintain strict AA/AAA contrast ratios against light backgrounds.
- **Role Accents (Noir Functional Tags):**
  - *Administrador:* High-contrast Alabaster / White frame with subtle Prussian backing.
  - *Recepción:* Prussian Blue badges with white technical labeling.
  - *Técnico:* Amber (`#fca311`) border focus, micro-grid status arrays.
  - *Almacén:* Muted steel-slate tinting for SKU/rack verification.
  - *Cliente:* Simplified high-legibility monochrome with amber action buttons.

## Typography

The typographical pairing mirrors technical instrumentation combined with high-contrast editorial hierarchy:

- **Display & Section Headers:** Uses geometric, structured grotesque architecture to evoke modernist technical blueprints and industrial machinery plaques. Letter-spacing is slightly contracted in larger scales for authoritative presence.
- **Body & Operational Copy:** Engineered for prolonged reading across workbenches, incident descriptions, and inspection logs. Neutral, exceptionally legible, and balanced.
- **Labels, Telemetry & Data Tables:** Driven by a precise monospaced engine. All hardware serials (MAC, IMEI, Service Tags), micro-status tags, timestamps, part numbers, and diagnostic values must render in this monospaced typeface with tabular figures enabled (`tnum: 1`) to preserve column alignment across data grids.

## Layout & Spacing

The layout operates on a modular 8px spatial grid with an underlying 12-column structural engine optimized for high-density hardware telemetry.

### Screen Adaptations & Grid
- **Desktop (1280px+):** 12-column fluid grid, `2.5rem` margins, `1.5rem` gutters. Accommodates the multi-pane split workbench view (e.g., Ticket Queue | Hardware Telemetry & Schematics | Diagnostic Checklist | Inventory Dispatch).
- **Tablet (768px - 1279px):** 8-column grid, `1.5rem` margins, `1rem` gutters. Collapses diagnostic splits into stacked panels with persistent sidebar navigation for field technicians and intake reception.
- **Mobile (< 768px):** 4-column grid, `1rem` margins, `1rem` gutters. Views reflow into single-column operational stacks with persistent bottom action strips for quick status changes and barcode scans.

### Workbench Role Layouts
- **Administrador:** Multi-card analytics dashboard with compact grid density, global revenue telemetry, bottleneck counters, and systemic audit logs.
- **Recepción:** Split-screen intake mode: Fast customer lookup and hardware entry form on the left, instant thermal printer receipt preview and camera/QR asset tagger on the right.
- **Técnico:** Focus-oriented diagnostic bench: Central ticket detail with hardware test suites, component swap audit trails, monospaced log input, and right-hand amber action dock.
- **Almacén:** High-contrast barcode/SKU picker layout, shelf-location matrix badges, and inventory threshold indicator tables.
- **Cliente:** Simplified, high-contrast tracking timeline detailing intake, diagnosis, quote approval, repair, QA, and pickup phases.

## Elevation & Depth

This design system avoids blurry drop shadows in favor of **structural boundaries and tonal containment**, directly reinforcing the Film Noir aesthetic.

### Depth Mechanics
- **Ground Floor (Canvas):** Pure `#000000` (Dark Mode) or `#ffffff` (Light Mode).
- **First Elevation (Containers & Modules):** Surface shifts to deep prussian slate `#0a1120` or `#14213d` (Dark Mode) bounded by a crisp `1px solid rgba(229, 229, 229, 0.12)`.
- **Second Elevation (Overlays, Flyouts & Modals):** Background elevated to `#14213d` bounded by a `1px solid #fca311` amber boundary or `1px solid #e5e5e5`. Drop shadows are exclusively crisp, zero-spread occlusions: `0 8px 0px 0px rgba(0, 0, 0, 0.9)`.
- **Optical Focus (Diagnostic Glow):** Active elements emit an ultra-contained 2px amber aura (`0 0 8px rgba(252, 163, 17, 0.3)`) rather than diffuse ambient shadows, mimicking oscilloscope and vacuum tube luminescence in a dark workshop.

## Shapes

The shape architecture is uncompromisingly **Sharp (`0`)**. 

All visual entities—cards, buttons, inputs, data tables, modals, and telemetry gauges—utilize `0px` border radii. Corners are clean, architectural right angles, communicating machined precision, aluminum casing edges, and printed circuit board layouts.

- **Chamfered Alternates:** For designated industrial tags, QR codes, and hardware ID labels, optional `clip-path` 45-degree chamfered cuts (4px) may be applied at the upper-right corner to indicate physical asset tags.
- **Dividers & Delimiters:** 1px hairline rules without soft feathering or blur.

## Components

### Buttons
- **Primary (Industrial Amber):** Background `#fca311`, text `#000000` (bold monospaced uppercase), `0px` radius, 1px solid `#fca311`. Hover state: background `#ffffff`, text `#000000`, border `#ffffff`. Active: translates 1px down.
- **Secondary (Prussian Precision):** Background `#14213d`, text `#ffffff`, 1px solid `#e5e5e5`. Hover: background `#e5e5e5`, text `#000000`.
- **Tertiary / Ghost (Noir Wire):** Transparent background, 1px solid `rgba(229, 229, 229, 0.25)`, text `#e5e5e5`. Hover: border `#fca311`, text `#fca311`.

### Data Tables & Ticket Queues
- Tables possess structural 1px gridlines throughout (`border-collapse: collapse`).
- Header cells feature `#14213d` background, all-caps monospaced labels (`label-sm`), with `#e5e5e5` text.
- Alternate rows utilize micro-tonal striping (`#000000` alternating with `#050912`).
- Row hover yields an instant `1px inset 0 0 0 1px #fca311` highlight. Serials, IMEIs, and timestamps are locked to the monospaced font token.

### Input Fields & Controls
- **Inputs:** `#000000` field surface, 1px solid `rgba(229, 229, 229, 0.3)` border, `#ffffff` monospaced text. Focus state triggers a sharp `1px solid #fca311` border with zero border-radius. Placeholder text renders in muted alabaster (`rgba(229, 229, 229, 0.4)`).
- **Checkboxes & Radios:** Sharp square inputs with 1px solid `#e5e5e5`. Checked state fills solid `#fca311` with a pitch-black inner mechanical square indicator. Emojis are strictly prohibited; custom SVG vector paths only.

### Status Badges & Chips
- Monospaced all-caps micro-indicators with 1px perimeter borders:
  - *Ingresado / En Cola:* Border `#e5e5e5`, text `#e5e5e5`, background transparent.
  - *En Diagnóstico:* Border `#14213d`, text `#ffffff`, background `#14213d`.
  - *Esperando Repuesto:* Border `#fca311`, text `#fca311`, background `rgba(252, 163, 17, 0.08)`.
  - *Reparado / Control de Calidad:* Border `#ffffff`, text `#000000`, background `#ffffff`.
  - *Crítico / Irreparable:* Border `#d90429`, text `#d90429`, background `rgba(217, 4, 41, 0.1)`.

### Cards & Modules
- Modular containers framed in `#14213d` or `rgba(229, 229, 229, 0.15)` with flat jet-black interior backgrounds.
- Card titles carry a top horizontal 2px accent stripe—default `#14213d`, switching to `#fca311` when selected or flagged for immediate technical intervention.

### Hardware Telemetry Gauge & Timeline (Client/Tech)
- Horizontal segmented bars composed of discrete rectangular blocks rather than continuous rounded progress bars.
- Diagnostic logs utilize real-time terminal output formatting with monospaced timestamps prepended to each status change event.