import json

with open('db_users_companies.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

print("COMPANIES:")
for c in data['companies']:
    print(f"Kode: {c.get('KodePartner')}, Nama: {c.get('NamaPartner')}, Jenis: {c.get('JenisUsaha')}")
    if 'klinik' in str(c.get('NamaPartner')).lower() or 'apotek' in str(c.get('NamaPartner')).lower() or 'apotik' in str(c.get('NamaPartner')).lower():
        print("  -> MATCH", c)

print("\nUSERS:")
for u in data['users']:
    if 'Apotek' in u['name'] or 'Klinik' in u['name'] or 'apotik' in u['name'].lower():
        print(f"Name: {u['name']}, Email: {u['email']}, OwnerID: {u.get('RecordOwnerID')}, Tipe: {u.get('RecordOwnerType')}")
