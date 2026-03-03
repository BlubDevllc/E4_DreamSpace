#  ONTWERP_DOCUMENT - Design & Requirements

Technisch ontwerp gebaseerd op de casus van DreamScape Interactive.

---

##  Inhoudsopgave

1. [Casus Samenvatting](#casus-samenvatting)
2. [User Personas](#user-personas)
3. [User Stories per Feature](#user-stories-per-feature)
4. [Acceptatiecriteria](#acceptatiecriteria)
5. [Definition of Done](#definition-of-done)
6. [Dataflow Diagrammen](#dataflow-diagrammen)
7. [Architectuur Overzicht](#architectuur-overzicht)

---

##  Casus Samenvatting

### Opdrachtgever
**DreamScape Interactive** - Een game-bedrijf dat een inventarisbeheersingssysteem nodig heeft.

### Opdracht
Ontwerp en bouw een systeem waarmee:
- Spelers accounts kunnen aanmaken en inloggen
- Spelers virtuele items kunnen beheren
- Spelers items kunnen verhandelen met elkaar
- Beheerders het systeem kunnen administreren

### Doelgroep
- **Spelers:** Casual tot hardcore gamers
- **Beheerders:** Game moderators en system administrators

---

##  User Personas

### 1. Sarah - De Casual Speler
- Leeftijd: 26 jaar
- Speelt 3-4x per week
- Zoekt naar social gaming experience
- Wil items verzamelen en ruilen
- **Pains:** Wil makkelijk items beheren zonder gecompliceerde menus

### 2. Marcus - De Hardcore Gamer
- Leeftijd: 22 jaar
- Speelt dagelijks
- Focust op item-grinding
- Wil optimale statistieken
- **Pains:** Wil snel items kunnen vergelijken en filteren

### 3. Alex - De Game Moderator (Admin)
- Leeftijd: 28 jaar
- Beheert 500+ spelers
- Monitort game economie
- Bant problematische accounts
- **Pains:** Wil rapport's zien over item-distributie voor balancing

---

##  User Stories per Feature

### Feature 1: User Registration & Authentication

#### US-1: Registratie
```
Als nieuwe speler
Wil ik een account kunnen aanmaken met gebruikersnaam en wachtwoord
Zodat ik aan het spel kan beginnen

Acceptatiecriteria:
  ☐ Registratieformulier met username, email, password, confirm password
  ☐ Validatie: username uniek, email geldig, password sterk (8+ chars, mix)
  ☐ Bevestigingsmail naar email adres
  ☐ Account inactief tot email verification
  ☐ Duidelijke foutmeldingen bij validatiefouten
```

#### US-2: Inloggen  
```
Als speler
Wil ik veilig kunnen inloggen met mijn credentials
Zodat ik mijn persoonlijke account en inventaris kan zien

Acceptatiecriteria:
  ☐ Inlogpagina met username en password
  ☐ Remember me optie (30 dagen)
  ☐ Foutmelding bij foutieve credentials
  ☐ JWT token generatie na succesvolle login
  ☐ Redirect naar dashboard
  ☐ Wachtwoord vergeten link
```

#### US-3: Profielbeheer
```
Als speler
Wil ik mijn profielgegevens kunnen bekijken en aanpassen
Zodat ik mijn account up-to-date kan houden

Acceptatiecriteria:
  ☐ Profielpagina met huidige gegevens
  ☐ Kunnen bewerken: email, wachtwoord
  ☐ Wachtwoord change met huidige wachtwoord verification
  ☐ Bevestigingsbericht na opslaan
  ☐ Account delete optie
```

---

### Feature 2: Item Catalogus & Browsing

#### US-4: Item Catalogus Bekijken
```
Als speler
Wil ik door alle beschikbare items kunnen bladeren
Zodat ik items kan vinden die ik wil hebben

Acceptatiecriteria:
  ☐ Item list met thumbnail, naam, type, rarity
  ☐ Filters: Type (Wapen/Armor/Accessoire), Rarity
  ☐ Zoekfunctie op itemnaam
  ☐ Sorteeroptie: Naam, Rarity, Stats
  ☐ Pagination (20 items per pagina)
  ☐ Item count display
```

#### US-5: Item Details
```
Als speler
Wil ik volledige details van een item kunnen zien
Zodat ik goed geïnformeerde keuzes kan maken

Acceptatiecriteria:
  ☐ Detailpagina met: naam, beschrijving, type, rarity
  ☐ Alle stats: Kracht, Snelheid, Duurzaamheid (0-100 schaal)
  ☐ Magische eigenschappen tonen
  ☐ Visueel stat bar representation
  ☐ Toon hoeveel spelers dit item hebben
  ☐ Relacioneerde items suggesties
```

---

### Feature 3: Persoonlijke Inventaris

#### US-6: Inventaris Bekijken
```
Als speler
Wil ik mijn inventaris kunnen bekijken
Zodat ik weet welke items ik heb

Acceptatiecriteria:
  ☐ Inventory pagina met alle mijn items
  ☐ Display: Item icon, naam, type, stats
  ☐ Totaal items count
  ☐ Filters: Type, Rarity
  ☐ Sortering: Naam, Rarity, Acquisition date
  ☐ Actie buttons: View details, Trade, Drop
```

#### US-7: Item Beheren
```
Als speler  
Wil ik items kunnen organiseren in mijn inventaris
Zodat ik snel items kan vinden

Acceptatiecriteria:
  ☐ Favorieten markeren
  ☐ Items sorteren
  ☐ Items verwijderen (met bevestiging)
  ☐ Item details popup
  ☐ Multiple select voor bulk actions
```

---

### Feature 4: Handelssysteem

#### US-8: Trade Proposal
```
Als speler
Wil ik mijn items kunnen ruilen met anderen
Zodat ik mijn inventaris kan diversifiëren

Acceptatiecriteria:
  ☐ Trade form: select offered item + requested item + target user
  ☐ Validatie: Ik bezit aangeboden item, ander ziet aangevraagde item
  ☐ Trade proposal in database
  ☐ Notificatie naar andere speler
  ☐ Bevestigingsbericht
  ☐ Kunnen transactie annuleren zolang pending
```

#### US-9: Trade Decisions
```
Als speler
Wil ik trade proposals kunnen accepteren of afwijzen
Zodat ik zelf bepaal welke trades plaatsvinden

Acceptatiecriteria:
  ☐ Pending trades list
  ☐ Details van voorstel: wie, welke items, wanneer
  ☐ Accept button: voert trade uit, items wisselen
  ☐ Reject button: tranactie annuleren
  ☐ Notificatie naar afzender
  ☐ Trade in historie zichtbaar
```

#### US-10: Trade History
```
Als speler
Wil ik mijn handelsgeschiedenis bekijken
Zodat ik kan tracking welke trades ik heb gedaan

Acceptatiecriteria:
  ☐ Trade history pagina met alle trades
  ☐ Filter: Completed, Rejected, Cancelled
  ☐ Details: Datum, partner, items, status
  ☐ Sorteer op datum (recent first)
```

---

### Feature 5: Notificaties

#### US-11: Notificatie Centrum
```
Als speler
Wil ik notificaties ontvangen over handelsactiviteiten
Zodat ik geen trades mis

Acceptatiecriteria:
  ☐ Notification bell icon in header
  ☐ Unread notification count
  ☐ Notification center modal/page
  ☐ Typen: Trade Request, Trade Accepted, Trade Rejected
  ☐ Klikken opent relevante pagina
  ☐ Mark as read functionality
  ☐ Delete notification optie
```

---

### Feature 6: Admin Panel

#### US-12: User Management
```
Als beheerder
Wil ik alle gebruikers kunnen beheren
Zodat ik ongepaste accounts kan saneren

Acceptatiecriteria:
  ☐ Admin panel met gebruikerslijst
  ☐ Zoeken op username/email
  ☐ Kan nieuwe account aanmaken
  ☐ Kan account deactiveren
  ☐ Kan account verwijderen
  ☐ Audit log van actions
```

#### US-13: Item Management
```
Als beheerder
Wil ik items kunnen create/edit/delete
Zodat ik inhoud beheer

Acceptatiecriteria:
  ☐ Item management interface
  ☐ Create: Form met alle fields (naam, desc, type, stats)
  ☐ Edit: Bestaande item wijzigen
  ☐ Delete: Item verwijderen met bevestiging
  ☐ Bulk upload mogelijkheid (CSV)
  ☐ Changelog van wijzigingen
```

#### US-14: Item Distribution
```
Als beheerder
Wil ik items handmatig aan spelers toekennen
Zodat ik bugs kan fixen of spelers kan belonen

Acceptatiecriteria:
  ☐ Search speler by username
  ☐ Select item from catalogus
  ☐ Give reason (bug fix, reward, test)
  ☐ Item appears in player inventory
  ☐ Player receives notification
  ☐ Audit trail
```

#### US-15: Game Statistics
```
Als beheerder
Wil ik statistieken zien over item distribution
Zodat ik game economie kan balanceren

Acceptatiecriteria:
  ☐ Dashboard met key metrics:
    - Total users
    - Active users (last 7 days)
    - Total trades completed
    - Items in circulation
  ☐ Item distribution: hoeveel spelers per item
  ☐ Rarity distribution
  ☐ Export to CSV
  ☐ Time range filters
```

---

## ✅ Acceptatiecriteria

### Functionele Requirements
- [ ] User kan registreren en email verifiëren
- [ ] User kan inloggen met JWT authentication
- [ ] User kan items in catalogus bladeren met filters
- [ ] User kan eigen inventaris beheren
- [ ] User kan trade proposals doen naar andere users
- [ ] User kan trade accepteren/afwijzen
- [ ] User krijgt notificaties over handelsactiviteiten
- [ ] Admin kan gebruikers beheren
- [ ] Admin kan items aanmaken/bewerken/verwijderen
- [ ] Admin kan statistics zien

### Non-Functionele Requirements
- [ ] Response time API < 200ms
- [ ] Database queries geïndexeerd
- [ ] Supports 1000+ concurrent users
- [ ] HTTPS encryption
- [ ] Input validation beide client & server
- [ ] Proper error handling overal

### Security Requirements
- [ ] Passwords gehashed met Bcrypt
- [ ] JWT token expiration (15 min)
- [ ] Role-based access control
- [ ] SQL injection prevention
- [ ] XSS protection
- [ ] CSRF tokens

---

## 🏁 Definition of Done

Een user story is **DONE** wanneer:

✅ **Development**
- [ ] Code geschreven per requirements
- [ ] Code is peer-reviewed
- [ ] Code passed linting (ESLint, Prettier)
- [ ] Unit tests geschreven (min 80% coverage)
- [ ] All unit tests passing

✅ **Testing**
- [ ] Manual testing uitgevoerd
- [ ] All acceptance criteria tested
- [ ] Edge cases getest
- [ ] No console errors

✅ **Documentation**
- [ ] Code comments waar nodig
- [ ] API endpoints gedocumenteerd
- [ ] Changes updated in CHANGELOG
- [ ] Database schema updates documented

✅ **Integration**
- [ ] Frontend integreert correct met API
- [ ] Database migrations executed
- [ ] No conflicts met andere branches
- [ ] Tested op staging environment

---

## 🔄 Dataflow Diagrammen

### Registration Flow
```
┌─────────────┐
│   Register  │
│   Form      │
└──────┬──────┘
       │
       ▼
┌──────────────────────┐
│ Validate Input       │
│ - Username unique?   │
│ - Email valid?       │
│ - Password strong?   │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│ Hash Password        │
│ (Bcrypt)             │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│ Save to Database     │
│ GEBRUIKER table      │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│ Send Verification    │
│ Email                │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│ Account Created      │
│ (status: inactive)   │
└──────────────────────┘
```

### Trade Flow
```
┌──────────────────┐
│   Player A       │
│ Propose Trade    │
└────────┬─────────┘
         │
         ▼
┌─────────────────────────────────┐
│ Create HANDELSVOORSTEL          │
│ Status: In Afwachting           │
└────────┬────────────────────────┘
         │
         ▼
┌────────────────────────────┐
│ Create NOTIFICATIE         │
│ For: Player B              │
│ Type: HandelsVerzoek       │
└────────┬───────────────────┘
         │
         ├─── Player B Accepts
         │        ▼
         │   ┌──────────────────┐
         │   │ Update Inventaris│
         │   │ A loses item 1   │
         │   │ A gains item 2   │
         │   │ B loses item 2   │
         │   │ B gains item 1   │
         │   └──────┬───────────┘
         │          │
         │          ▼
         │   ┌──────────────────┐
         │   │ Update Trade     │
         │   │ Status: Accepted │
         │   └──────┬───────────┘
         │          │
         └──────┬───┘
                │
                ▼
         ┌──────────────────┐
         │ Create Notif for │
         │ A: Trade Accept  │
         └──────────────────┘
         
         ├─── Player B Rejects
         │        ▼
         │   ┌──────────────────┐
         │   │ Update Trade     │
         │   │ Status: Rejected │
         │   └──────┬───────────┘
         │          │
         └──────┬───┘
                │
                ▼
         ┌──────────────────┐
         │ Create Notif for │
         │ A: Trade Reject  │
         └──────────────────┘
```

---

## 🏗️ Architectuur Overzicht

```
┌─────────────────────────────────────┐
│   CLIENT LAYER (React/Browser)      │
│   - Pages, Components, State        │
│   - HTTP Requests (Axios)           │
│   - Token storage (localStorage)    │
└────────────────┬────────────────────┘
                 │ HTTP/HTTPS
                 ▼
┌─────────────────────────────────────┐
│   API LAYER (Express.js)            │
│   - Authentication middleware       │
│   - Authorization checks            │
│   - Request validation              │
│   - Business logic (Controllers)    │
│   - Database queries (Models)       │
└────────────────┬────────────────────┘
                 │ SQL
                 ▼
┌─────────────────────────────────────┐
│   DATABASE LAYER (PostgreSQL)       │
│   - GEBRUIKER table                 │
│   - ITEM table                      │
│   - INVENTARIS table                │
│   - HANDELSVOORSTEL table           │
│   - NOTIFICATIE table               │
└─────────────────────────────────────┘
```

---

**Version:** 1.0  
**Last Updated:** March 3, 2026
