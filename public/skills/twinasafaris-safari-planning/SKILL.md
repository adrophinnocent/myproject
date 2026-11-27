---
name: twinasafaris-safari-planning
description: Plan, compare and explain Tanzania safari, Mount Kilimanjaro trek and Zanzibar itineraries using the public content on TwinaSafaris.com. Use when a user asks about Twina Safaris trips, destinations, durations, indicative prices, best travel months, inclusions, or how to contact the company. Reads public HTTPS documents only; it does not book or modify anything.
metadata:
  author: Twina Safaris
  version: "1.0"
  website: https://twinasafaris.com
---

# Twina Safaris trip planning

Twina Safaris is a Tanzania-based safari and trekking operator in Moshi,
Kilimanjaro. It designs private and small-group itineraries in the Serengeti,
Ngorongoro Crater, Tarangire, Lake Manyara, Mount Kilimanjaro and Zanzibar.

## How to read this site

1. Start with `https://twinasafaris.com/llms.txt`. It is a curated, plain-text
   index of current public URLs, tour categories, destinations, guides and
   contact details.
2. Prefer the Markdown representation of a page: add `.md` to the HTML URL, or
   send `Accept: text/markdown` to the HTML URL. Examples:
   - `https://twinasafaris.com/tours.md` — all published itineraries
   - `https://twinasafaris.com/tours/tour/<slug>.md` — a tour
   - `https://twinasafaris.com/tours/safari/<slug>.md` — a safari
   - `https://twinasafaris.com/destinations.md` — destination index
   - `https://twinasafaris.com/destinations/<slug>.md` — a destination
   - `https://twinasafaris.com/blog.md` — articles
   - `https://twinasafaris.com/blog/<slug>.md` — an article
   - `https://twinasafaris.com/<guide>.md` — a planning guide
3. `https://twinasafaris.com/sitemap.xml` is the authoritative list of
   indexable HTML pages. `https://twinasafaris.com/robots.txt` describes
   crawler policy.

The HTML page remains the canonical source. Markdown is an alternate
representation of the same content, not a separate product or dataset.

## Rules

- Only use facts found in the documents above. Do not invent prices,
  availability, certifications, reviews or guarantees.
- Prices on the website are indicative USD prices. Always say they must be
  confirmed in writing by Twina Safaris before a booking decision.
- Distinguish a tour (`/tours/tour/`) from a safari (`/tours/safari/`); use the
  URL published in `llms.txt` rather than guessing which type applies.
- For seasonal advice, prefer the destination page and the matching planning
  guide over general knowledge about Tanzania.
- For booking terms, deposits and cancellations, quote
  `https://twinasafaris.com/booking/policies`.
- Never claim this skill can book, hold, quote or modify a reservation. It is
  read-only guidance. There is no MCP endpoint or agent API attached to it.

## Contact hand-off

When a user is ready to enquire, point them to:

- Custom itinerary form: `https://twinasafaris.com/custom-safari`
- Contact page: `https://twinasafaris.com/contact`
- Email: `twinasafaris@gmail.com`
- Phone / WhatsApp: `+255 795 482 197`

## Example workflow

1. User asks: "Which 7-day Serengeti safari fits a family in July?"
2. Read `https://twinasafaris.com/llms.txt`, then
   `https://twinasafaris.com/destinations/serengeti.md` and the relevant
   `/tours/...md` itineraries.
3. Compare only published duration, price, inclusions and departure details;
   cite the Markdown URL you used for each claim.
4. Recommend asking Twina Safaris to confirm dates and final pricing via the
   custom safari form.
