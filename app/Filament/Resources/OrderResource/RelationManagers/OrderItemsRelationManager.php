<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItemsDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
 
                Select::make('item_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set, $livewire) {
                        //    dd("Hello");
                        $product_id = $get('item_id');
                        $product = Product::find($product_id);
                        $price = $product->price;
                        // dd($price);
                        $set('price', $price);


                        $subtotal = $get('qty') * $price;

                        $set('subtotal', $subtotal);
                    }),
                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')->numeric()
                    ->debounce(1500)
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set, $livewire) {
                        if($get('qty') != "" && is_numeric($get('qty'))){
                            $price = $get('price');

                            $subtotal = $get('qty') * $price;
    
                            $set('subtotal', $subtotal);
                        }
                       
                    }),
                Forms\Components\TextInput::make('price')->numeric(),
                Forms\Components\TextInput::make('subtotal')->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('subtotal'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->after(function ($record) {
                    $order = Order::find($record->order_id);
                    $orderItems = OrderItems::where('order_id', $record->order_id)->sum('subtotal');
                    $order->totalamount = $orderItems;
                    $order->update();
                })->mutateFormDataUsing(function (Tables\Actions\CreateAction $action, array $data): array {
                    $record = $this->getOwnerRecord();
               
                  $orderItems = OrderItems::where('order_id', $record->id)->where('item_id',$data['item_id'])->first(); 
                  if ($orderItems) {
                    $recipient = auth()->user();

                    Notification::make()
                    ->title('Item Already in USE')
                    ->danger()
                    ->send();
                    Notification::make()
                        ->title('Item Already in USE')
                        ->danger()
                       ->sendToDatabase($recipient);
                 $action->halt();
                }

                return $data;
                  //dd($orderItems);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->after(function ($record) {
                    $order = Order::find($record->order_id);
                    $orderItems = OrderItems::where('order_id', $record->order_id)->sum('subtotal');
                    $order->totalamount = $orderItems;
                    // dd($order); 
                    $order->update();

                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
