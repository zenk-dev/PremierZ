class CreateStaffEvents < ActiveRecord::Migration
  def change
    create_table :staff_events do |t|
      t.references :staff_member, null: false # �E�����R�[�h�ւ̊O���L�[
      t.string :type, null: false             # �C�x���g�^�C�v
      t.datetime :created_at, null: false     # ��������
    end

    add_index :staff_events, :created_at
    add_index :staff_events, [ :staff_member_id, :created_at ]
    add_foreign_key :staff_events, :staff_members
  end
end
