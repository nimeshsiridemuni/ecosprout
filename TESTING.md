# EcoSprout Manual Test Checklist

Record the actual result and attach a screenshot for each test used in the
assignment report. Do not mark a test as passed until it has been performed.

| ID | Test | Expected result | Actual result | Status |
|---|---|---|---|---|
| T01 | Register valid customer | Account is created | | Not tested |
| T02 | Register duplicate email | Registration is rejected | | Not tested |
| T03 | Login with each role | Correct dashboard opens | | Not tested |
| T04 | Login with wrong password | Login is rejected | | Not tested |
| T05 | Customer opens staff/admin URL | Access is denied | | Not tested |
| T06 | Search plant name/category | Matching database plants appear | | Not tested |
| T07 | Open invalid plant ID | 404 message appears | | Not tested |
| T08 | Add/update/remove cart item | Cart and totals update | | Not tested |
| T09 | Order more than stock | Checkout is rejected | | Not tested |
| T10 | Complete checkout | Order, items and payment are stored | | Not tested |
| T11 | Complete checkout | Stock decreases | | Not tested |
| T12 | Book future service date | Pending booking is stored | | Not tested |
| T13 | Book past service date | Booking is rejected | | Not tested |
| T14 | Staff updates booking | New status is stored | | Not tested |
| T15 | Register for workshop twice | Duplicate is rejected | | Not tested |
| T16 | Register for full workshop | Registration is rejected | | Not tested |
| T17 | Submit inquiry | Inquiry is stored as New | | Not tested |
| T18 | Staff responds to inquiry | Customer can read response | | Not tested |
| T19 | Staff adds/edits/deactivates plant | Catalogue reflects change | | Not tested |
| T20 | Staff manages service/workshop | Public page reflects change | | Not tested |
| T21 | Admin creates staff account | Hashed Staff account is stored | | Not tested |
| T22 | Admin changes another user | Role/status is updated | | Not tested |
| T23 | Admin opens reports | Current database totals appear | | Not tested |
| T24 | Test narrow mobile width | Pages remain readable and usable | | Not tested |
| T25 | Checkout with the original normalized orders table | Order completes without a customer_name SQL error | | Not tested |
| T26 | Open a cart after a plant is deactivated or stock falls | Unavailable items are removed or quantities are reduced | | Not tested |
| T27 | Cancel a paid order once | Stock is restored and Paid changes to Refunded | | Not tested |
| T28 | Attempt to reopen a cancelled order | Request is rejected so stock cannot be deducted incorrectly | | Not tested |
