# TradeFlow — Entity Relationship Overview

TradeFlow is a **multi-tenant** buying-house platform. Most business tables include `tenant_id` and use a global scope for isolation.

## Tenancy & access

```mermaid
erDiagram
    Tenant ||--o{ TenantDomain : has
    Tenant ||--o{ TenantModuleSetting : enables
    Tenant ||--o{ User : employs
    Tenant ||--o{ Company : owns
    User }o--|| Tenant : current_tenant
```

| Entity | Purpose |
|--------|---------|
| `tenants` | Buying house / customer account |
| `tenant_domains` | Hostnames for subdomain routing |
| `tenant_module_settings` | Per-tenant module toggles |
| `users` | Staff and portal users (`portal_type`, Spatie roles) |

## Partners & catalog

```mermaid
erDiagram
    Tenant ||--o{ Buyer : has
    Tenant ||--o{ Factory : has
    Tenant ||--o{ Supplier : has
    ProductCategory ||--o{ Style : contains
    Buyer ||--o{ Style : sources
    Style ||--o{ StyleColor : has
    Style ||--o{ StyleSize : has
```

## Commercial → orders → production

```mermaid
erDiagram
    Buyer ||--o{ BuyerPurchaseOrder : places
    BuyerPurchaseOrder ||--o{ BuyerPurchaseOrderItem : lines
    BuyerPurchaseOrder ||--o{ FactoryAllocation : splits
    Factory ||--o{ FactoryAllocation : receives
    FactoryAllocation ||--o{ FactoryWorkOrder : generates
    FactoryWorkOrder ||--o{ ProductionPlan : plans
    ProductionPlan ||--o{ ProductionUpdate : reports
    FactoryWorkOrder ||--o{ Inspection : quality
```

## Logistics & finance

```mermaid
erDiagram
    BuyerPurchaseOrder ||--o{ Shipment : ships
    Shipment ||--o{ ShipmentItem : contains
    Buyer ||--o{ LetterOfCredit : finances
    LetterOfCredit ||--o{ LcAmendment : amends
    BuyerPurchaseOrder ||--o{ Invoice : bills
```

## Workflow

| Entity | Links |
|--------|--------|
| `tasks` | Optional morph `taskable`, assigned user |
| `approvals` | Morph `approvable` approval chain |
| `audit_logs` / `activity_logs` | Cross-cutting observability |
| `notification_preferences` | Per-user channel/event toggles |

## Demo tenant snapshot

The `DemoSeeder` provisions **Global Apparel Sourcing Ltd** (`GAS`, slug `global-apparel`) with representative rows across the entities above for local evaluation.
