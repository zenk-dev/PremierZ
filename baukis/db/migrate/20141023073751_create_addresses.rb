class CreateAddresses < ActiveRecord::Migration
  def change
    create_table :addresses do |t|
      t.references :customer, null: false               # ŒÚ‹q‚Ö‚ÌŠO•”ƒL[
      t.string :type, null: false                       # Œp³ƒJƒ‰ƒ€
      t.string :postal_code, null: false                # —X•Ö”Ô†
      t.string :prefecture, null: false                 # “s“¹•{Œ§
      t.string :city, null: false                       # s‹æ’¬‘º
      t.string :address1, null: false                   # ’¬ˆæA”Ô’n“™
      t.string :address2, null: false                   # Œš•¨–¼A•”‰®”Ô†“™
      t.string :company_name, null: false, default: ''  # ‰ïĞ–¼
      t.string :division_name, null: false, default: '' # •”–¼

      t.timestamps
    end

    add_index :addresses, :customer_id
    add_index :addresses, [ :type, :customer_id ], unique: true
    add_foreign_key :addresses, :customers
  end
end
