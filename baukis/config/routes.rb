Rails.application.routes.draw do
  constraints host: 'baukis.example.com' do
    namespace :staff, path: '' do
      root 'top#index'
      get 'login' => 'sessions#new', as: :login
      post 'session' => 'sessions#create', as: :session
      delete 'session' => 'sessions#destroy'
    end
  end

  namespace :admin do
    root 'top#index'
    get 'login' => 'sessions#new', as: :login
    post 'session' => 'sessions#create', as: :session
    delete 'session' => 'sessions#destroy'
  end

  namespace :customer do
    root 'top#index'
  end

  root 'errors#not_found'
  get '*anything' => 'errors#not_found'
end
