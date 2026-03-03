# 🗄️ DATABASE_DESIGN - Schema & Normalisatie

Volledig database ontwerp voor DreamSpace Inventory System.

---

##  Inhoudsopgave

1. [Database Overview](#database-overview)
2. [Normalisatie Proces](#normalisatie-proces)
3. [Tabel Definities](#tabel-definities)
4. [Relaties & Constraints](#relaties--constraints)
5. [Indexering Strategie](#indexering-strategie)
6. [Data Dictionary](#data-dictionary)
7. [SQL Schema Script](#sql-schema-script)

---

##  Database Overview

### Database Naam
```
dreamspace_db
```

### Totaal Tabellen: 5

| Tabel | Records | Beschrijving |
|-------|---------|-------------|
| **GEBRUIKER** | ~1000-10K | User accounts |
| **ITEM** | ~100-500 | Item catalogus |
| **INVENTARIS** | ~5K-50K | User items owned |
| **HANDELSVOORSTEL** | ~10K-100K | Trade transactions |
| **NOTIFICATIE** | ~50K-500K | User notifications |

---

##  Normalisatie Proces

### Stap 1: Verzamelen Gegevensvereisten

**Uit User Stories:** 
- User data: username, email, password, role
- Item data: naam, beschrijving, type, stats
- Inventory data: user-item relatie, quantity, date
- Trade data: participants, items, status, date
- Notification data: recipient, type, message, status

### Stap 2: Unnormalized Format (Flat)
```
| UserID | Username | Email | Items_Owned | Trades_History | Notifications |
```

**Problem:** Repeating groups, data redundancy

### Stap 3: 1NF - Atomic Values
Iedere kolom bevat single value (geen arrays):

```
GEBRUIKER
├─ UserID (INT)
├─ Username (VARCHAR)
├─ Email (VARCHAR)
└─ Role (ENUM)

ITEM
├─ ItemID (INT)
├─ Naam (VARCHAR)
└─ Type (ENUM)

INVENTARIS
├─ InventoryID (INT)
├─ UserID_FK (INT)
├─ ItemID_FK (INT)
└─ Quantity (INT)
```

### Stap 4: 2NF - No Partial Dependencies
Alle non-key kolommen fully dependent van PRIMARY KEY:

 **Bad:**
```
| InventoryID | UserID | Username | ItemID | ItemName |
             ↑──────────────────┘(Username depends op UserID, not InventoryID)
```

 **Good:**
```
INVENTARIS: | InventoryID | UserID | ItemID | Quantity |
GEBRUIKER:  | UserID | Username |
ITEM:       | ItemID | ItemName |
```

### Stap 5: 3NF - No Transitive Dependencies
Non-key kolommen dependent ONLY op primary key:

```
 HANDELSVOORSTEL
   PK: TradeID
   ├─ TradeID → VerzenderID (direct)
   ├─ TradeID → OntvangerID (direct)
   ├─ TradeID → Status (direct)
   └─ TradeID → CreatedAt (direct)
   
 NOT: TradeID → UserDetails (via VerzenderID)
```

### Resultaat: Fully Normalized 3NF Schema 

```
┌─────────────────┐
│   GEBRUIKER     │
├─────────────────┤
│ UserID (PK)     │
│ Username (U)    │
│ Email (U)       │
│ Role            │
└─────────────────┘
    ▲     ▲
    │     │
┌───┴─────┴──────┐ 
│   INVENTARIS   │
├────────────────┤
│ InvID (PK)     │
│ UserID (FK)    │
│ ItemID (FK)    │
└────────────────┘
         ▲
         │
    ┌────┴──────────┐
    │      ITEM     │
    ├───────────────┤
    │ ItemID (PK)   │
    │ Naam          │
    │ Type          │
    │ Stats (0-100) │
    └───────────────┘
```

---

##  Tabel Definities

### 1. GEBRUIKER (Users)

```sql
CREATE TABLE GEBRUIKER (
  UserID            INT PRIMARY KEY AUTO_INCREMENT,
  Gebruikersnaam    VARCHAR(50)  NOT NULL UNIQUE,
  Wachtwoord        VARCHAR(255) NOT NULL,  -- Bcrypt hash
  Email             VARCHAR(100) NOT NULL UNIQUE,
  Rol               ENUM('Speler', 'Beheerder') NOT NULL DEFAULT 'Speler',
  CreatedAt         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UpdatedAt         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  IsActief          BOOLEAN DEFAULT TRUE,
  
  INDEX idx_username (Gebruikersnaam),
  INDEX idx_email (Email),
  INDEX idx_role (Rol)
);
```

**Beschrijving:**
- `UserID`: Unique user identifier
- `Gebruikersnaam`: Unique login name (3-50 chars)
- `Wachtwoord`: Bcrypt hashed (never plain text!)
- `Email`: Unique email (for recovery, verification)
- `Rol`: Either 'Speler' or 'Beheerder'
- `IsActief`: account status (can be deactivated)

**Sample Data:**
```
UserID=1, Gebruikersnaam='ShadowSlayer', Email='shadow@example.com', Rol='Speler'
UserID=4, Gebruikersnaam='AdminMaster',  Email='admin@example.com',  Rol='Beheerder'
```

---

### 2. ITEM (Items Catalogus)

```sql
CREATE TABLE ITEM (
  ItemID              INT PRIMARY KEY AUTO_INCREMENT,
  Naam                VARCHAR(100) NOT NULL,
  Beschrijving        TEXT NOT NULL,
  Type                ENUM('Wapen', 'Armor', 'Accessoire') NOT NULL,
  Zeldzaamheid        ENUM('Algemeen', 'Zeldzaam', 'Episch', 'Legendarisch') NOT NULL,
  Kracht              INT NOT NULL CHECK (Kracht >= 0 AND Kracht <= 100),
  Snelheid            INT NOT NULL CHECK (Snelheid >= 0 AND Snelheid <= 100),
  Duurzaamheid        INT NOT NULL CHECK (Duurzaamheid >= 0 AND Duurzaamheid <= 100),
  MagischeEigenschappen TEXT,
  CreatedAt           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UpdatedAt           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_type (Type),
  INDEX idx_rarity (Zeldzaamheid),
  FULLTEXT INDEX ft_search (Naam, Beschrijving)
);
```

**Beschrijving:**
- `ItemID`: Unique item in catalogus
- `Type`: Classification van item
- `Zeldzaamheid`: Affects value (Algemeen < Legendarisch)
- `Kracht/Snelheid/Duurzaamheid`: Stats (0-100 scale)
- `MagischeEigenschappen`: Special abilities text
- FULLTEXT index for efficient searching

**Sample Data:**
```
ItemID=1, Naam='Zwaard des Vuur', Type='Wapen', Zeldzaamheid='Legendarisch',
          Kracht=90, Snelheid=60, Duurzaamheid=80
```

---

### 3. INVENTARIS (User Inventory)

```sql
CREATE TABLE INVENTARIS (
  InventarisID      INT PRIMARY KEY AUTO_INCREMENT,
  UserID            INT NOT NULL,
  ItemID            INT NOT NULL,
  Hoeveelheid       INT NOT NULL DEFAULT 1 CHECK (Hoeveelheid >= 1),
  DatumAangeschaft  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (UserID) REFERENCES GEBRUIKER(UserID) ON DELETE CASCADE,
  FOREIGN KEY (ItemID) REFERENCES ITEM(ItemID) ON DELETE CASCADE,
  UNIQUE (UserID, ItemID),  -- User kan elk item maximum 1x hebben
  
  INDEX idx_user (UserID),
  INDEX idx_item (ItemID)
);
```

**Beschrijving:**
- `InventarisID`: Unique record ID
- `UserID` + `ItemID`: Links naar user en item
- `Hoeveelheid`: Quantity (normalement 1, future extensie)
- `DatumAangeschaft`: When user acquired item
- UNIQUE constraint: User-Item combination unique (can't have duplicates)

**Sample Data:**
```
InventarisID=1, UserID=1, ItemID=1, Hoeveelheid=1, DatumAangeschaft='2024-02-01'
```

**Voorbeeld Query:**
```sql
-- Get all items van ShadowSlayer
SELECT i.Naam, i.Type, i.Zeldzaamheid
FROM INVENTARIS inv
JOIN ITEM i ON inv.ItemID = i.ItemID
WHERE inv.UserID = 1;

Result:
Naam='Zwaard des Vuur', Type='Wapen', Zeldzaamheid='Legendarisch'
Naam='Schaduw Mantel', Type='Armor', Zeldzaamheid='Zeldzaam'
```

---

### 4. HANDELSVOORSTEL (Trade Proposals)

```sql
CREATE TABLE HANDELSVOORSTEL (
  TradeID              INT PRIMARY KEY AUTO_INCREMENT,
  VerzenderID          INT NOT NULL,
  OntvangerID          INT NOT NULL,
  VerzendItemID        INT NOT NULL,
  AngevraaagdeItemID   INT NOT NULL,
  Status               ENUM('In Afwachting', 'Geaccepteerd', 'Afgewezen', 'Geannuleerd')
                       NOT NULL DEFAULT 'In Afwachting',
  CreatedAt            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UpdatedAt            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (VerzenderID) REFERENCES GEBRUIKER(UserID) ON DELETE RESTRICT,
  FOREIGN KEY (OntvangerID) REFERENCES GEBRUIKER(UserID) ON DELETE RESTRICT,
  FOREIGN KEY (VerzendItemID) REFERENCES ITEM(ItemID) ON DELETE RESTRICT,
  FOREIGN KEY (AngevraaagdeItemID) REFERENCES ITEM(ItemID) ON DELETE RESTRICT,
  
  CHECK (VerzenderID != OntvangerID),  -- Can't trade with yourself
  
  INDEX idx_verzender (VerzenderID),
  INDEX idx_ontvanger (OntvangerID),
  INDEX idx_status (Status)
);
```

**Beschrijving:**
- `TradeID`: Unique trade proposal
- `VerzenderID`: User making offer
- `OntvangerID`: User receiving offer
- `VerzendItemID`: Item being offered
- `AngevraaagdeItemID`: Item being requested
- `Status`: Lifecycle van trade (Pending → Accepted/Rejected/Cancelled)
- CHECK constraint: Prevents self-trading

**Sample Data:**
```
TradeID=1, VerzenderID=1, OntvangerID=2, 
           VerzendItemID=1, AngevraaagdeItemID=2,
           Status='In Afwachting'
```

**Voorbeeld Query:**
```sql
-- Alle pending trades voor OntvangerID=2
SELECT 
  u.Gebruikersnaam AS From,
  i1.Naam AS Offering,
  i2.Naam AS Requesting
FROM HANDELSVOORSTEL t
JOIN GEBRUIKER u ON t.VerzenderID = u.UserID
JOIN ITEM i1 ON t.VerzendItemID = i1.ItemID
JOIN ITEM i2 ON t.AngevraaagdeItemID = i2.ItemID
WHERE t.OntvangerID = 2 AND t.Status = 'In Afwachting';
```

---

### 5. NOTIFICATIE (Notifications)

```sql
CREATE TABLE NOTIFICATIE (
  NotificatieID    INT PRIMARY KEY AUTO_INCREMENT,
  UserID           INT NOT NULL,
  Type             ENUM('HandelsVerzoek', 'HandelsGeaccepteerd', 'HandelsAfgewezen')
                   NOT NULL,
  Bericht          VARCHAR(500) NOT NULL,
  RelatedTradeID   INT,
  Gelezen          BOOLEAN NOT NULL DEFAULT FALSE,
  CreatedAt        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (UserID) REFERENCES GEBRUIKER(UserID) ON DELETE CASCADE,
  FOREIGN KEY (RelatedTradeID) REFERENCES HANDELSVOORSTEL(TradeID) ON DELETE SET NULL,
  
  INDEX idx_user (UserID),
  INDEX idx_read (Gelezen),
  INDEX idx_created (CreatedAt)
);
```

**Beschrijving:**
- `NotificatieID`: Unique notification
- `UserID`: Who receives notification
- `Type`: Category (trade request, acceptance, rejection)
- `Bericht`: Notification message text
- `RelatedTradeID`: Links to relevant trade (nullable)
- `Gelezen`: Read status for notification center

**Sample Data:**
```
NotificatieID=1, UserID=2, Type='HandelsVerzoek',
                 Bericht='ShadowSlayer wants to trade',
                 RelatedTradeID=1
```

---

##  Relaties & Constraints

### Relatie Diagram

```
┌──────────────────┐
│    GEBRUIKER     │ (1)
│ (UserID=PK)      │
└────────┬─────────┘
         │ (one-to-many)
         │
    ┌────┴──────────────────┬──────────────────┐
    │                       │                  │
    ▼ (many)            (many)              (many)
┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│   INVENTARIS     │  │ HANDELSVOORSTEL  │  │   NOTIFICATIE    │
│ (InventarisID=PK)│  │ (TradeID=PK)     │  │ (NotificatieID=PK)
│ UserID_FK ───────┼──┘ VerzenderID_FK ──-–┘
│ ItemID_FK ──┐    │  OntvangerID_FK
└──────────────┼───┘  │
               │      │
               ▼  (1) ▼
            ┌──────────────────┐
            │      ITEM        │
            │ (ItemID=PK)      │
            └──────────────────┘
```

### Constraint Types

**Foreign Keys:**
```
INVENTARIS.UserID → GEBRUIKER.UserID (ON DELETE CASCADE)
INVENTARIS.ItemID → ITEM.ItemID (ON DELETE CASCADE)
HANDELSVOORSTEL.VerzenderID → GEBRUIKER.UserID (ON DELETE RESTRICT)
HANDELSVOORSTEL.OntvangerID → GEBRUIKER.UserID (ON DELETE RESTRICT)
HANDELSVOORSTEL.VerzendItemID → ITEM.ItemID (ON DELETE RESTRICT)
HANDELSVOORSTEL.AngevraaagdeItemID → ITEM.ItemID (ON DELETE RESTRICT)
NOTIFICATIE.UserID → GEBRUIKER.UserID (ON DELETE CASCADE)
NOTIFICATIE.RelatedTradeID → HANDELSVOORSTEL.TradeID (ON DELETE SET NULL)
```

**Unique Constraints:**
```
GEBRUIKER.Gebruikersnaam (UNIQUE)
GEBRUIKER.Email (UNIQUE)
INVENTARIS.(UserID, ItemID) (UNIQUE) -- User can own max 1 of each item
```

**Check Constraints:**
```
ITEM.Kracht CHECK (>= 0 AND <= 100)
ITEM.Snelheid CHECK (>= 0 AND <= 100)
ITEM.Duurzaamheid CHECK (>= 0 AND <= 100)
INVENTARIS.Hoeveelheid CHECK (>= 1)
HANDELSVOORSTEL CHECK (VerzenderID != OntvangerID)
```

---

##  Indexering Strategie

### Waarom Indexeren?

Indexes versnellen SQL queries (vooral SELECT en WHERE):

```
Zonder index: O(n) - zoeken alle rows
Met index: O(log n) - binary search
```

### Geïndexeerde Kolommen

```sql
-- GEBRUIKER
CREATE INDEX idx_username ON GEBRUIKER(Gebruikersnaam);
CREATE INDEX idx_email ON GEBRUIKER(Email);
CREATE INDEX idx_role ON GEBRUIKER(Rol);

-- ITEM  
CREATE INDEX idx_type ON ITEM(Type);
CREATE INDEX idx_rarity ON ITEM(Zeldzaamheid);
CREATE FULLTEXT INDEX ft_search ON ITEM(Naam, Beschrijving);

-- INVENTARIS
CREATE INDEX idx_user ON INVENTARIS(UserID);
CREATE INDEX idx_item ON INVENTARIS(ItemID);

-- HANDELSVOORSTEL
CREATE INDEX idx_verzender ON HANDELSVOORSTEL(VerzenderID);
CREATE INDEX idx_ontvanger ON HANDELSVOORSTEL(OntvangerID);
CREATE INDEX idx_status ON HANDELSVOORSTEL(Status);

-- NOTIFICATIE
CREATE INDEX idx_user ON NOTIFICATIE(UserID);
CREATE INDEX idx_read ON NOTIFICATIE(Gelezen);
CREATE INDEX idx_created ON NOTIFICATIE(CreatedAt);
```

### Index Performance Impact

| Scenario | Without Index | With Index | Speedup |
|----------|---------------|-----------|---------|
| Find user by username | 0.5s (500K rows) | 5ms | **100x** |
| List items by type | 0.3s | 10ms | **30x** |
| User inventory | 0.2s | 2ms | **100x** |

---

##  Data Dictionary

| Kolom | Datatype | Size | Nullable | Default | Description |
|-------|----------|------|----------|---------|-------------|
| **GEBRUIKER** | | | | | |
| UserID | INT | 4 | NO | AUTO | Primary key |
| Gebruikersnaam | VARCHAR | 50 | NO | - | Login name, UNIQUE |
| Wachtwoord | VARCHAR | 255 | NO | - | Bcrypt hash |
| Email | VARCHAR | 100 | NO | - | Email, UNIQUE |
| Rol | ENUM | 1 | NO | 'Speler' | Role type |
| CreatedAt | TIMESTAMP | 8 | NO | NOW() | Account creation |
| UpdatedAt | TIMESTAMP | 8 | NO | NOW() | Last update |
| IsActief | BOOLEAN | 1 | NO | TRUE | Active status |
| **ITEM** | | | | | |
| ItemID | INT | 4 | NO | AUTO | Primary key |
| Naam | VARCHAR | 100 | NO | - | Item name |
| Beschrijving | TEXT | 65535 | NO | - | Full description |
| Type | ENUM | 1 | NO | - | Wapen/Armor/Accessorie |
| Zeldzaamheid | ENUM | 1 | NO | - | Rarity level |
| Kracht | INT | 4 | NO | - | 0-100 stat |
| Snelheid | INT | 4 | NO | - | 0-100 stat |
| Duurzaamheid | INT | 4 | NO | - | 0-100 stat |
| MagischeEigenschappen | TEXT | 65535 | YES | NULL | Special abilities |
| **INVENTARIS** | | | | | |
| InventarisID | INT | 4 | NO | AUTO | Primary key |
| UserID | INT | 4 | NO | - | FK to GEBRUIKER |
| ItemID | INT | 4 | NO | - | FK to ITEM |
| Hoeveelheid | INT | 4 | NO | 1 | Quantity (min 1) |
| DatumAangeschaft | TIMESTAMP | 8 | NO | NOW() | Acquisition date |

---

##  SQL Schema Script

**File:** `database/schema.sql`

```sql
-- Create database
CREATE DATABASE IF NOT EXISTS dreamspace_db 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE dreamspace_db;

-- Enable foreign keys
SET FOREIGN_KEY_CHECKS = 1;

-- Create tables (see above section)
-- Then import test data from database/seeds.sql
```

---

**Version:** 1.0  
**Last Updated:** March 3, 2026
