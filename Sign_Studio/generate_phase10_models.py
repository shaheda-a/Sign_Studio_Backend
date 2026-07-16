import os

models_dir = 'app/Models/'

models = {
    'WarrantyCard': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyCard extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function issuedBy() { return $this->belongsTo(User::class, 'issued_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'CustomerFeedback': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFeedback extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customer_feedbacks';
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function collectedBy() { return $this->belongsTo(User::class, 'collected_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'ServiceTicket': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTicket extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function warrantyCard() { return $this->belongsTo(WarrantyCard::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function assignments() { return $this->hasMany(ServiceAssignment::class, 'ticket_id'); }
    public function resolutions() { return $this->hasMany(ServiceResolution::class, 'ticket_id'); }
    public function quotations() { return $this->hasMany(ServiceQuotation::class, 'ticket_id'); }
}
''',
    'ServiceAssignment': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAssignment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'ServiceResolution': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceResolution extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function resolvedBy() { return $this->belongsTo(User::class, 'resolved_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'ServiceQuotation': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceQuotation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'ServicePayment': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function quotation() { return $this->belongsTo(ServiceQuotation::class, 'service_quotation_id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'ServiceAsset': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAsset extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function history() { return $this->hasMany(ServiceHistory::class, 'service_asset_id'); }
}
''',
    'ServiceHistory': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'service_history';
    protected $guarded = [];

    public function asset() { return $this->belongsTo(ServiceAsset::class, 'service_asset_id'); }
    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function servicedBy() { return $this->belongsTo(User::class, 'serviced_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'AmcContract': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmcContract extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'Invoice': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function order() { return $this->belongsTo(Order::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function receipts() { return $this->hasMany(Receipt::class); }
    public function reminders() { return $this->hasMany(PaymentReminder::class); }
}
''',
    'InvoiceItem': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'Receipt': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'PaymentReminder': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'Expense': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'CashFlow': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'CustomerLedger': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'VendorLedger': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorLedger extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'Ledger': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'TdsRecord': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TdsRecord extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class, 'po_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
''',
    'ProjectCost': '''<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCost extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order() { return $this->belongsTo(Order::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
'''
}

for name, content in models.items():
    with open(os.path.join(models_dir, f"{name}.php"), 'w') as f:
        f.write(content)
        print(f"Generated {name}")
