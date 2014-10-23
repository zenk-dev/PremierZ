class CreateAddresses < ActiveRecord::Migration
  def change
    create_table :addresses do |t|
      t.references :customer, null: false               # �ڋq�ւ̊O���L�[
      t.string :type, null: false                       # �p���J����
      t.string :postal_code, null: false                # �X�֔ԍ�
      t.string :prefecture, null: false                 # �s���{��
      t.string :city, null: false                       # �s�撬��
      t.string :address1, null: false                   # ����A�Ԓn��
      t.string :address2, null: false                   # �������A�����ԍ���
      t.string :company_name, null: false, default: ''  # ��Ж�
      t.string :division_name, null: false, default: '' # ������

      t.timestamps
    end

    add_index :addresses, :customer_id
    add_index :addresses, [ :type, :customer_id ], unique: true
    add_foreign_key :addresses, :customers
  end
end
