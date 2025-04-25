            <div class="flex flex-col sm:flex-row sm:justify-end gap-2 mt-4">
              <Button variant="outline" size="sm" as="a" :href="route('orders.index')">
                <ArrowLeft class="h-4 w-4 mr-1" />
                Kembali ke Daftar Pesanan
              </Button>
              <Button v-if="order.documents_count > 0" variant="outline" size="sm" as="a" :href="route('orders.documents.index', order.id)">
                <Files class="h-4 w-4 mr-1" />
                Lihat Dokumen & Kredensial
              </Button>
            </div> 