# 🗄️ DreamSpace Database Setup - Laragon & phpMyAdmin

Stap-voor-stap handleiding om de DreamSpace database in te stellen met Laragon en phpMyAdmin.

---

## 📋 Inhoud

1. [Voorbereiding](#-voorbereiding)
2. [Database Importen via phpMyAdmin](#-database-importen-via-phpmyadmin)
3. [Verifying the Installation](#-verifying-the-installation)
4. [Troubleshooting](#-troubleshooting)

---

## ✅ Voorbereiding

### Vereisten
- ✓ Laragon geïnstalleerd en actief
- ✓ phpMyAdmin toegankelijk via `http://localhost/phpmyadmin`
- ✓ MySQL/MariaDB service draait in Laragon
- ✓ `dreamspace_schema.sql` bestand beschikbaar

### Bestanden Locatie
```
E4_DreamSpace/
└── database/
    └── dreamspace_schema.sql  ← Dit bestand gebruiken
```

---

## 🚀 Database Importen via phpMyAdmin

### Stap 1: Open phpMyAdmin

1. Start Laragon (klik op "Start All")
2. Open browser en ga naar: `http://localhost/phpmyadmin`
3. Je zou een inlogscherm zien (meestal al ingelogd bij default Laragon setup)

### Stap 2: Ga naar de Import Tab

1. In phpMyAdmin, klik op **"Import"** tab (bovenaan)
2. Je ziet nu het import-formulier

### Stap 3: Selecteer het SQL-bestand

1. Klik op **"Choose File"** knop
2. Navigate naar: `E4_DreamSpace/database/dreamspace_schema.sql`
3. Selecteer en open

### Stap 4: Start de Import

1. Scroll naar beneden en klik **"Go"** button
2. Wacht tot de import compleet is (groen bericht verschijnt)

### Stap 5: Verifieer Database

Na succesvolle import zie je:
```
✓ dreamspace_db database aangemaakt
✓ 5 tabellen created (GEBRUIKER, ITEM, INVENTARIS, HANDELSVOORSTEL, NOTIFICATIE)
✓ Test data geïnsereerd (5 users, 7 items, inventory data)
```

---

## ✔️ Verifying the Installation

### Via phpMyAdmin GUI

1. Klik op **"dreamspace_db"** in de left sidebar
2. Je zou alle 5 tabellen moeten zien:
   - ✓ GEBRUIKER
   - ✓ ITEM
   - ✓ INVENTARIS
   - ✓ HANDELSVOORSTEL
   - ✓ NOTIFICATIE

### Via SQL Query

Voer deze queries uit om te verifiëren:

```sql
-- 1. Count tables
SELECT COUNT(*) as TabellenAantal FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'dreamspace_db';
-- Expected result: 5

-- 2. Count users
SELECT COUNT(*) as GebruikersTotaal FROM GEBRUIKER;
-- Expected result: 5

-- 3. Count items
SELECT COUNT(*) as ItemsTotaal FROM ITEM;
-- Expected result: 7

-- 4. View all users
SELECT UserID, Gebruikersnaam, Rol, Email FROM GEBRUIKER;
-- Expected result: 5 rows with test data

-- 5. View user inventory
SELECT 
  u.Gebruikersnaam,
  i.Naam as ItemNaam,
  i.Type,
  i.Zeldzaamheid
FROM INVENTARIS inv
JOIN GEBRUIKER u ON inv.UserID = u.UserID
JOIN ITEM i ON inv.ItemID = i.ItemID;
-- Expected result: 7 rows showing inventory links
```

### Steps to Run Query in phpMyAdmin

1. Click on **dreamspace_db** database
2. Click on **"SQL"** tab
3. Paste the query
4. Click **"Go"** icon

---

## 🔐 Security Verification

### Password Hashing ✓

Alle test-users hebben al Bcrypt gehashte wachtwoorden:
- Plain text passwords: NOOIT opgeslagen
- Hash: `$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6`
- Dit is een irreversible hash - veilig in database

### Foreign Key Constraints ✓

```sql
-- Verify foreign keys
SELECT 
  CONSTRAINT_NAME,
  TABLE_NAME,
  REFERENCED_TABLE_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'dreamspace_db'
AND REFERENCED_TABLE_SCHEMA IS NOT NULL;
```

Expected constraints:
- ✓ INVENTARIS → GEBRUIKER (CASCADE)
- ✓ INVENTARIS → ITEM (CASCADE)
- ✓ HANDELSVOORSTEL → GEBRUIKER (RESTRICT - 2x)
- ✓ HANDELSVOORSTEL → ITEM (RESTRICT - 2x)
- ✓ NOTIFICATIE → GEBRUIKER (CASCADE)
- ✓ NOTIFICATIE → HANDELSVOORSTEL (SET NULL)

### Unique Constraints ✓

```sql
-- Verify unique indexes
SHOW INDEXES FROM GEBRUIKER WHERE Non_unique = 0;
```

Expected unique constraints:
- ✓ PRIMARY KEY: UserID
- ✓ UNIQUE: Gebruikersnaam
- ✓ UNIQUE: Email

### Check Constraints ✓

Database niveau validatie voor:
- ✓ Item stats: 0 <= Kracht/Snelheid/Duurzaamheid <= 100
- ✓ Inventory quantity: >= 1
- ✓ No self-trading: VerzenderID != OntvangerID

---

## 🔧 Troubleshooting

### Probleem: "Access Denied" Error

**Oorzaak:** MySQL nicht gestart of foutieve credentials

**Oplossing:**
1. Zorg dat Laragon "Start All" knop is ingedrukt
2. MySQL service moet groen zijn in Laragon interface
3. Restart Laragon als nodig

### Probleem: "database_does_not_exist" Error

**Oorzaak:** Database werd niet aangemaakt

**Oplossing:**
1. Zorg dat je de complete `.sql` file importeerde
2. Controleer dat geen errors waren in import result
3. Try import opnieuw

### Probleem: File Encoding Issues

**Oorzaak:** UTF-8 characters niet correct gelezen

**Oplossing:**
1. Zorg dat bestand in **UTF-8 encoding** is (geen BOM)
2. In phpMyAdmin import, zet "Character encoding" op **utf8mb4**
3. Retry import

### Probleem: "Cannot delete or update a parent row"

**Oorzaak:** Probeert data te verwijderen met foreign key constraints

**Oplossing:**
- Dit is INTENDED! Foreign keys voorkomen data inconsistency
- Je kunt users/items in trades niet verwijderen
- Zet trade status op 'Geannuleerd' eerst als nodig
- Verwijder dan related records

### Probleem: Blank Page na Import

**Oorzaak:** phpMyAdmin refresh didnt complete

**Oplossing:**
1. Refreshing de browser (F5)
2. Go back naar phpMyAdmin main page
3. Klik opnieuw op dreamspace_db in sidebar

---

## 📊 Database Schema Summary

### 5 Tabellen

| Tabel | Rijen | Beschrijving |
|-------|-------|-------------|
| **GEBRUIKER** | 5 test users | User accounts met role-based access |
| **ITEM** | 7 test items | Virtual items catalog met stats |
| **INVENTARIS** | 7 links | User-item ownership records |
| **HANDELSVOORSTEL** | 0 (empty) | Trade proposals (template ready) |
| **NOTIFICATIE** | 0 (empty) | User notifications (template ready) |

### Totale Rows: 12 + templates

---

## ✨ Security Features Implemented

### In Database ✓
- [x] Foreign key constraints (referential integrity)
- [x] Unique constraints (no duplicates)
- [x] Check constraints (data validation)
- [x] UTF-8 encoding (character encoding)
- [x] Indexes (query performance)
- [x] Timestamps (audit trail)

### In Application Layer (TODO)
- [ ] Password hashing with Bcrypt
- [ ] JWT token generation
- [ ] SQL injection prevention (parameterized queries)
- [ ] XSS protection (input escaping)
- [ ] CSRF token validation
- [ ] Rate limiting
- [ ] Input validation

---

## 🎯 Next Steps

1. ✅ **Database Created** (you are here)
2. 🔲 Create Node.js/Express backend
3. 🔲 Implement authentication endpoints
4. 🔲 Build API layer with security
5. 🔲 Create React frontend
6. 🔲 Test all features
7. 🔲 Deploy to production

---

## 📚 Additional Resources

- [Laragon Documentation](https://laragon.org/docs/)
- [phpMyAdmin Guide](https://www.phpmyadmin.net/)
- [MySQL Reference](https://dev.mysql.com/doc/)
- [DreamSpace Documentation](../ONTWERP_DOCUMENT.md)
- [Security & Privacy](../SECURITY_PRIVACY.md)

---

**Version:** 1.0  
**Last Updated:** March 3, 2026  
**Status:** Ready for use
