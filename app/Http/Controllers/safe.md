 1. Eloquent ORM — Food::create(), $food->update(), Food::all() use PDO prepared statements under hood. No raw SQL = no injection surface.
  2. $validated used, not $request->all() — only whitelisted fields pass to DB.
  3. Route model binding — Food $food resolves model safely, no manual ID interpolation.

  Potential gaps to harden:

  1. name allows any string — could tighten with regex
  2. No rate limiting — write endpoints unprotected from brute force
  3. No auth — anyone can create/update/delete