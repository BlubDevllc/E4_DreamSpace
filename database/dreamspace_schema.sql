-- ============================================================================
-- DREAMSPACE INVENTORY MANAGEMENT SYSTEM - Database Schema
-- Version: 1.0
-- Database: dreamspace_db
-- DBMS: MySQL / MariaDB (Laragon compatible)
-- ============================================================================
-- 
-- This SQL script creates the complete DreamSpace database with:
-- ✓ 5 normalized tables (3NF)
-- ✓ Foreign key constraints with appropriate actions
-- ✓ Unique constraints for data integrity
-- ✓ Check constraints for data validation
-- ✓ Strategic indexing for query performance
-- ✓ Security best practices (password hashing in app layer)
--
-- ============================================================================

-- Drop existing database (use with caution in production)
DROP DATABASE IF EXISTS dreamspace_db;

-- Create database
CREATE DATABASE dreamspace_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE dreamspace_db;

-- ============================================================================
-- TABLE 1: GEBRUIKER (Users)
-- ============================================================================
-- Stores user account information
-- Password field: Will store Bcrypt hashes from application layer
-- ============================================================================

CREATE TABLE GEBRUIKER (
  UserID INT PRIMARY KEY AUTO_INCREMENT,
  Gebruikersnaam VARCHAR(50) NOT NULL UNIQUE,
  Wachtwoord VARCHAR(255) NOT NULL COMMENT 'Bcrypt hashed password (never plaintext!)',
  Email VARCHAR(100) NOT NULL UNIQUE,
  Rol ENUM('Speler', 'Beheerder') NOT NULL DEFAULT 'Speler',
  IsActief BOOLEAN NOT NULL DEFAULT TRUE,
  CreatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UpdatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Indexes for common queries
  INDEX idx_username (Gebruikersnaam),
  INDEX idx_email (Email),
  INDEX idx_rol (Rol),
  INDEX idx_actief (IsActief)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='User accounts with authentication';

-- ============================================================================
-- TABLE 2: ITEM (Items Catalog)
-- ============================================================================
-- Stores virtual item definitions
-- Statistics are constrained to 0-100 range (enforced at database level)
-- ============================================================================

CREATE TABLE ITEM (
  ItemID INT PRIMARY KEY AUTO_INCREMENT,
  Naam VARCHAR(100) NOT NULL,
  Beschrijving TEXT NOT NULL,
  Type ENUM('Wapen', 'Armor', 'Accessoire') NOT NULL,
  Zeldzaamheid ENUM('Algemeen', 'Zeldzaam', 'Episch', 'Legendarisch') NOT NULL,
  Kracht INT NOT NULL DEFAULT 0 CHECK (Kracht >= 0 AND Kracht <= 100),
  Snelheid INT NOT NULL DEFAULT 0 CHECK (Snelheid >= 0 AND Snelheid <= 100),
  Duurzaamheid INT NOT NULL DEFAULT 0 CHECK (Duurzaamheid >= 0 AND Duurzaamheid <= 100),
  MagischeEigenschappen TEXT,
  CreatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UpdatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Indexes for filtering and searching
  INDEX idx_type (Type),
  INDEX idx_zeldzaamheid (Zeldzaamheid),
  FULLTEXT INDEX ft_search (Naam, Beschrijving)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Virtual items catalog with stats';

-- ============================================================================
-- TABLE 3: INVENTARIS (User Inventory)
-- ============================================================================
-- Links users to items they own
-- UNIQUE constraint prevents duplicate items per user
-- ============================================================================

CREATE TABLE INVENTARIS (
  InventarisID INT PRIMARY KEY AUTO_INCREMENT,
  UserID INT NOT NULL,
  ItemID INT NOT NULL,
  Hoeveelheid INT NOT NULL DEFAULT 1 CHECK (Hoeveelheid >= 1),
  DatumAangeschaft TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  -- Ensure each user-item combination is unique (no duplicates)
  UNIQUE KEY unique_user_item (UserID, ItemID),
  
  -- Foreign key constraints
  CONSTRAINT fk_inventaris_user 
    FOREIGN KEY (UserID) REFERENCES GEBRUIKER(UserID) 
    ON DELETE CASCADE,
  
  CONSTRAINT fk_inventaris_item 
    FOREIGN KEY (ItemID) REFERENCES ITEM(ItemID) 
    ON DELETE CASCADE,
  
  -- Indexes for efficient lookups
  INDEX idx_user (UserID),
  INDEX idx_item (ItemID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='User inventory - which items users own';

-- ============================================================================
-- TABLE 4: HANDELSVOORSTEL (Trade Proposals)
-- ============================================================================
-- Records trade proposals between players
-- CHECK constraint prevents self-trading
-- Status lifecycle: In Afwachting → Geaccepteerd/Afgewezen/Geannuleerd
-- ============================================================================

CREATE TABLE HANDELSVOORSTEL (
  TradeID INT PRIMARY KEY AUTO_INCREMENT,
  VerzenderID INT NOT NULL,
  OntvangerID INT NOT NULL,
  VerzendItemID INT NOT NULL,
  AangevraagdeItemID INT NOT NULL,
  Status ENUM('In Afwachting', 'Geaccepteerd', 'Afgewezen', 'Geannuleerd') 
         NOT NULL DEFAULT 'In Afwachting',
  CreatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UpdatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Prevent self-trading
  CHECK (VerzenderID != OntvangerID),
  
  -- Foreign key constraints
  CONSTRAINT fk_trade_verzender 
    FOREIGN KEY (VerzenderID) REFERENCES GEBRUIKER(UserID) 
    ON DELETE RESTRICT,
  
  CONSTRAINT fk_trade_ontvanger 
    FOREIGN KEY (OntvangerID) REFERENCES GEBRUIKER(UserID) 
    ON DELETE RESTRICT,
  
  CONSTRAINT fk_trade_verzend_item 
    FOREIGN KEY (VerzendItemID) REFERENCES ITEM(ItemID) 
    ON DELETE RESTRICT,
  
  CONSTRAINT fk_trade_aangevraagde_item 
    FOREIGN KEY (AangevraagdeItemID) REFERENCES ITEM(ItemID) 
    ON DELETE RESTRICT,
  
  -- Indexes for filtering by status and participants
  INDEX idx_verzender (VerzenderID),
  INDEX idx_ontvanger (OntvangerID),
  INDEX idx_status (Status),
  INDEX idx_created (CreatedAt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Trade proposals between players';

-- ============================================================================
-- TABLE 5: NOTIFICATIE (Notifications)
-- ============================================================================
-- Notifies users of trading activities
-- RelatedTradeID is nullable (not all notifications relate to trades)
-- ============================================================================

CREATE TABLE NOTIFICATIE (
  NotificatieID INT PRIMARY KEY AUTO_INCREMENT,
  UserID INT NOT NULL,
  Type ENUM('HandelsVerzoek', 'HandelsGeaccepteerd', 'HandelsAfgewezen', 
            'HandelsGeannuleerd', 'ItemAangeschaft', 'Systeem') 
       NOT NULL,
  Bericht VARCHAR(500) NOT NULL,
  RelatedTradeID INT,
  Gelezen BOOLEAN NOT NULL DEFAULT FALSE,
  CreatedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  -- Foreign key constraints
  CONSTRAINT fk_notificatie_user 
    FOREIGN KEY (UserID) REFERENCES GEBRUIKER(UserID) 
    ON DELETE CASCADE,
  
  CONSTRAINT fk_notificatie_trade 
    FOREIGN KEY (RelatedTradeID) REFERENCES HANDELSVOORSTEL(TradeID) 
    ON DELETE SET NULL,
  
  -- Indexes for efficient filtering
  INDEX idx_user (UserID),
  INDEX idx_gelezen (Gelezen),
  INDEX idx_created (CreatedAt),
  INDEX idx_type (Type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='User notifications for game activities';

-- ============================================================================
-- INITIAL TEST DATA (Optional - Comment out if not needed)
-- ============================================================================

-- Insert test users
INSERT INTO GEBRUIKER (Gebruikersnaam, Wachtwoord, Email, Rol, IsActief) VALUES
('ShadowSlayer', '$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6', 'shadow@example.com', 'Speler', TRUE),
('MysticMage', '$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6', 'mystic@example.com', 'Speler', TRUE),
('DragonKnight', '$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6', 'dragon@example.com', 'Speler', TRUE),
('AdminMaster', '$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6', 'admin@example.com', 'Beheerder', TRUE),
('ThunderRogue', '$2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6', 'thunder@example.com', 'Speler', TRUE);

-- Insert test items
INSERT INTO ITEM (Naam, Beschrijving, Type, Zeldzaamheid, Kracht, Snelheid, Duurzaamheid, MagischeEigenschappen) VALUES
('Zwaard des Vuur', 'Een mythisch zwaard met een vlammende gloed.', 'Wapen', 'Legendarisch', 90, 60, 80, '+30% vuurschade'),
('IJs Amulet', 'Een amulet dat de drager beschermt tegen kou.', 'Accessoire', 'Episch', 20, 10, 70, '+25% weerstand tegen ijsaanvallen'),
('Schaduw Mantel', 'Een donkere mantel die je bewegingen verbergt.', 'Armor', 'Zeldzaam', 40, 85, 50, '+15% kans om aanvallen te ontwijken'),
('Hamer der Titanen', 'Een massieve hamer met de kracht van de aarde.', 'Wapen', 'Legendarisch', 95, 40, 90, 'Kan vijanden 3 sec verdoven'),
('Lichtboog', 'Een boog die pijlen van pure energie afvuurt.', 'Wapen', 'Episch', 85, 75, 60, '+10% kans op kritieke schade'),
('Helende Ring', 'Een ring die de gezondheid van de drager herstelt.', 'Accessoire', 'Zeldzaam', 10, 5, 100, '+5 HP per seconde'),
('Demonen Harnas', 'Een verdoemd harnas met duistere krachten.', 'Armor', 'Legendarisch', 75, 50, 95, 'Absorbeert 20% van ontvangen schade');

-- Insert test inventory (some users own some items)
INSERT INTO INVENTARIS (UserID, ItemID, Hoeveelheid) VALUES
(1, 1, 1),  -- ShadowSlayer owns Zwaard des Vuur
(1, 3, 1),  -- ShadowSlayer owns Schaduw Mantel
(2, 2, 1),  -- MysticMage owns IJs Amulet
(2, 5, 1),  -- MysticMage owns Lichtboog
(3, 4, 1),  -- DragonKnight owns Hamer der Titanen
(3, 7, 1),  -- DragonKnight owns Demonen Harnas
(5, 6, 1);  -- ThunderRogue owns Helende Ring

-- ============================================================================
-- SECURITY NOTES & IMPLEMENTATION REMINDERS
-- ============================================================================
-- 
-- PASSWORD SECURITY:
-- • Passwords in the Wachtwoord column are Bcrypt hashes
-- • Sample hash: $2b$10$N9qo8uLOickgx2ZOUZ.N9Ozxl91NHzPWn4qZrfLN5m7M0jHSO/.y6
-- • This hash represents "Test123!" but is irreversible
-- • Application layer must hash passwords before storing: bcrypt(plaintext)
-- • Never store plaintext passwords in the database
-- • Never transmit passwords over HTTP (always use HTTPS)
--
-- FOREIGN KEY CONSTRAINTS:
-- • GEBRUIKER and ITEM use ON DELETE RESTRICT for HANDELSVOORSTEL
--   (Cannot delete users/items involved in trades)
-- • INVENTARIS uses ON DELETE CASCADE
--   (When user/item deleted, their inventory entries are removed)
-- • NOTIFICATIE uses ON DELETE CASCADE for users but SET NULL for trades
--   (Notif persists if trade is deleted, but references are cleared)
--
-- UNIQUE CONSTRAINTS:
-- • Gebruikersnaam and Email must be unique (no duplicates)
-- • UserID + ItemID combination in INVENTARIS must be unique
--   (User cannot own the same item twice)
--
-- CHECK CONSTRAINTS:
-- • Item stats (Kracht, Snelheid, Duurzaamheid) must be 0-100
-- • Inventory quantity must be >= 1
-- • Trade must not be between same user (VerzenderID != OntvangerID)
--
-- INDEXES:
-- • Username and Email indexed (fast login lookups)
-- • Item Type and Rarity indexed (fast filtering)
-- • UserID and ItemID indexed in INVENTARIS (fast inventory queries)
-- • Status indexed in HANDELSVOORSTEL (fast trade status filtering)
-- • FULLTEXT index on Item name/description (fast text search)
--
-- IMPLEMENTATION CHECKLIST:
-- ✓ All 5 tables created with proper relationships
-- ✓ Foreign key constraints implemented with proper ON DELETE actions
-- ✓ Unique constraints for username and email (no duplicates)
-- ✓ Check constraints for stat ranges (0-100)
-- ✓ Strategic indexing for query performance
-- ✓ UTF-8 character encoding (supports international characters)
-- ✓ Timestamps for audit trail (CreatedAt, UpdatedAt)
-- ✓ Proper enum types for role and status (data integrity)
-- ✓ Test data included for development
-- ✓ Comments documenting security considerations
--
-- NEXT STEPS FOR SECURITY:
-- 1. Configure HTTPS in web server configuration
-- 2. Implement JWT token generation in application layer
-- 3. Implement password hashing with Bcrypt (min 10 salt rounds)
-- 4. Add SQL injection prevention (parameterized queries)
-- 5. Implement role-based access control (RBAC) in application
-- 6. Add rate limiting for login attempts
-- 7. Implement CSRF token validation
-- 8. Add input validation on both client and server
-- 9. Setup audit logging for sensitive operations
-- 10. Regular security testing and penetration testing
--
-- ============================================================================

-- Verification queries (run these to test the schema)
-- SELECT COUNT(*) FROM GEBRUIKER;
-- SELECT * FROM ITEM;
-- SELECT u.Gebruikersnaam, i.Naam FROM INVENTARIS inv
--   JOIN GEBRUIKER u ON inv.UserID = u.UserID
--   JOIN ITEM i ON inv.ItemID = i.ItemID;

-- ============================================================================
-- END OF DATABASE SCHEMA
-- ============================================================================
